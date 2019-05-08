<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Observer;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Controller\Page\ViewContext;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Block\Category\View as LandingPageView;
use Emico\AttributeLanding\Block\OverviewPage\View as OverviewPageView;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;
use Magento\Theme\Block\Html\Title;

class SeoObserver implements ObserverInterface
{
    /**
     * @var Config
     */
    private $pageConfig;

    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * MetaTagsObserver constructor.
     * @param Config $pageConfig
     * @param LandingPageContext $landingPageContext
     */
    public function __construct(Config $pageConfig, LandingPageContext $landingPageContext)
    {
        $this->pageConfig = $pageConfig;
        $this->landingPageContext = $landingPageContext;
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
            $this->setCanonicalUrl($page);
        }
    }

    /**
     * @param LandingPageInterface $landingPage
     */
    protected function setCanonicalUrl(LandingPageInterface $landingPage)
    {
        $this->pageConfig->addRemotePageAsset(
            $landingPage->getCanonicalUrl() ?? $landingPage->getUrlPath(),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );
    }
}