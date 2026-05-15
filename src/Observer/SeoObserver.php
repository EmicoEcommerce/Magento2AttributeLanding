<?php

/**
 * @author        Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Observer;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Block\OverviewPage\View as OverviewPageView;
use Emico\AttributeLanding\Model\Config;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Category\View as LandingPageView;
use Magento\Framework\App\Request\Http as MagentoHttpRequest;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Title;

class SeoObserver implements ObserverInterface
{
    /**
     * MetaTagsObserver constructor.
     *
     * @param PageConfig            $pageConfig
     * @param Config                $config
     * @param LandingPageContext    $landingPageContext
     * @param CategoryRepositoryInterface $categoryRepository
     * @param StoreManagerInterface $storeManager
     * @param MagentoHttpRequest    $request
     */
    public function __construct(
        private readonly PageConfig $pageConfig,
        private readonly Config $config,
        private readonly LandingPageContext $landingPageContext,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly StoreManagerInterface $storeManager,
        protected MagentoHttpRequest $request,
    ) {
    }

    /**
     * @param Observer $observer
     *
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
        /** @phpstan-ignore-next-line */
        if (!$page) {
            $page = $this->landingPageContext->getOverviewPage();
            /** @phpstan-ignore-next-line */
            if (!$page) {
                return;
            }
        }

        $this->pageConfig->getTitle()->set($page->getMetaTitle());
        $this->pageConfig->setDescription($page->getMetaDescription());
        $this->pageConfig->setKeywords($page->getMetaKeywords());

        /** @var Title $pageMainTitle */
        $pageMainTitle = $block->getLayout()->getBlock('page.main.title');
        /** @phpstan-ignore-next-line */
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($page->getHeading());
        }

        /** @phpstan-ignore-next-line */
        if (!($page instanceof LandingPageInterface)) {
            return;
        }

        $this->clearCurrentCanonical($page);
        $this->setLandingPageCanonicalUrl($page);
    }

    /**
     * @param LandingPageInterface $landingPage
     *
     * @return void
     */
    protected function setLandingPageCanonicalUrl(LandingPageInterface $landingPage)
    {
        $canonicalUrl = $this->getCanonicalUrl($landingPage);

        $this->pageConfig->addRemotePageAsset(
            $canonicalUrl,
            'canonical',
            ['attributes' => ['rel' => 'canonical']],
        );
    }

    /**
     * @param LandingPageInterface $landingPage
     *
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

            /** @phpstan-ignore-next-line */
            return $this->storeManager->getStore()->getUrl($landingPage->getUrlPath(), $params);
        }

        /** @phpstan-ignore-next-line */
        return $this->storeManager->getStore()->getUrl('', ['_direct' => $landingPage->getUrlPath()]);
    }

    /**
     * Clear
     *
     * @param LandingPageInterface $landingPage
     *
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function clearCurrentCanonical(LandingPageInterface $landingPage)
    {
        if (!$landingPage->getCategoryId()) {
            return;
        }

        $category = $this->categoryRepository->get($landingPage->getCategoryId());
        /** @phpstan-ignore-next-line */
        $this->pageConfig->getAssetCollection()->remove($category->getUrl());
    }
}
