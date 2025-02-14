<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Observer;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Controller\Page\ViewContext;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Category\View as LandingPageView;
use Emico\AttributeLanding\Block\OverviewPage\View as OverviewPageView;
use Magento\Framework\App\Request\Http as MagentoHttpRequest;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Title;
use Emico\AttributeLanding\Model\Config;

class SeoObserver implements ObserverInterface
{
    /**
     * @var PageConfig $pageConfig
     */
    private PageConfig $pageConfig;

    /**
     * @var Config $config
     */
    private Config $config;

    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var MagentoHttpRequest $request
     */
    protected MagentoHttpRequest $request;

    /**
     * MetaTagsObserver constructor.
     * @param PageConfig $pageConfig
     * @param Config $config
     * @param LandingPageContext $landingPageContext
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        PageConfig $pageConfig,
        Config $config,
        LandingPageContext $landingPageContext,
        CategoryRepositoryInterface $categoryRepository,
        StoreManagerInterFace $storeManager,
        MagentoHttpRequest $request
    ) {
        $this->pageConfig = $pageConfig;
        $this->config = $config;
        $this->landingPageContext = $landingPageContext;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $block = $observer->getData('block');
        if (!$block instanceof LandingPageView && !$block instanceof OverviewPageView) {
            return;
        }

        $page = $this->landingPageContext->getLandingPage();
        if (!$page) {
            $page = $this->landingPageContext->getOverviewPage();
            if (!$page) {
                return;
            }
        }

        $this->pageConfig->getTitle()->set($page->getMetaTitle());
        $this->pageConfig->setDescription($page->getMetaDescription());
        $this->pageConfig->setKeywords($page->getMetaKeywords());

        /** @var Title $pageMainTitle */
        $pageMainTitle = $block->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($page->getHeading());
        }

        if ($page instanceof LandingPageInterface) {
            $this->clearCurrentCanonical($page);
            $this->setLandingPageCanonicalUrl($page);
        }
    }

    /**
     * @param LandingPageInterface $landingPage
     */
    protected function setLandingPageCanonicalUrl(LandingPageInterface $landingPage)
    {
        $canonicalUrl = $this->getCanonicalUrl($landingPage);

        $this->pageConfig->addRemotePageAsset(
            $canonicalUrl,
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );
    }

    /**
     * @param LandingPageInterface $landingPage
     * @return null|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getCanonicalUrl(LandingPageInterface $landingPage)
    {
        if ($landingPage->getCanonicalUrl()) {
            return $landingPage->getCanonicalUrl();
        }

        if ($this->config->isCanonicalSelfReferencingEnabled()) {
            $params['_current'] = true;
            $params['_use_rewrite'] = true;
            $params['_escape'] = false;
            $params['query'] = $this->request->getQuery();

            return $this->storeManager->getStore()->getUrl($landingPage->getUrlPath(), $params);
        }

        return $this->storeManager->getStore()->getUrl('', ['_direct' => $landingPage->getUrlPath()]);
    }

    /**
     * Clear
     *
     * @param LandingPageInterface $landingPage
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function clearCurrentCanonical(LandingPageInterface $landingPage)
    {
        if (!empty($landingPage->getCategoryId())) {
            $category = $this->categoryRepository->get($landingPage->getCategoryId());
            $this->pageConfig->getAssetCollection()->remove($category->getUrl());
        }
    }
}
