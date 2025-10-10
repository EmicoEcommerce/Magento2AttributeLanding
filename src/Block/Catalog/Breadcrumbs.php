<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

/**
 * Adds landing page breadcrumbs
 * We have no way to overwrite category breadcrumbs using events or plugins.
 * So we need to extend the Magento\Catalog\Block\Breadcrumbs breadcrumbs block
 */

namespace Emico\AttributeLanding\Block\Catalog;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Block\Breadcrumbs as CatalogBreadcrumbs;
use Magento\Catalog\Helper\Data;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;

class Breadcrumbs extends CatalogBreadcrumbs
{
    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * @var OverviewPageRepositoryInterface
     */
    private $overviewPageRepository;

    /**
     * @param Context $context
     * @param Data $catalogData
     * @param LandingPageContext $landingPageContext
     * @param OverviewPageRepositoryInterface $overviewPageRepository
     * @param UrlInterface $urlInterface
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $catalogData,
        LandingPageContext $landingPageContext,
        OverviewPageRepositoryInterface $overviewPageRepository,
        private UrlInterface $urlInterface,
        array $data = []
    ) {
        parent::__construct($context, $catalogData, $data);
        $this->landingPageContext = $landingPageContext;
        $this->overviewPageRepository = $overviewPageRepository;
    }

    /**
     * Preparing layout
     *
     * @return \Magento\Catalog\Block\Breadcrumbs
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareLayout()
    {
        $landingPage = $this->landingPageContext->getLandingPage();
        /** @phpstan-ignore-next-line */
        if ($landingPage === null) {
            return parent::_prepareLayout();
        }

        $this->addLandingPageBreadCrumbs($landingPage);
        return $this;
    }

    /**
     * @param LandingPageInterface $landingPage
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function addLandingPageBreadCrumbs(LandingPageInterface $landingPage): void
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');

        if (!$breadcrumbsBlock) {
            return;
        }

        /** @phpstan-ignore-next-line */
        $breadcrumbsBlock->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $this->_storeManager->getStore()->getBaseUrl() // @phpstan-ignore-line
            ]
        );

        /** @phpstan-ignore-next-line */
        if ($landingPage->getOverviewPageId() !== null) {
            $overviewPage = $this->overviewPageRepository->getById($landingPage->getOverviewPageId());
            /** @phpstan-ignore-next-line */
            $breadcrumbsBlock->addCrumb(
                'overviewpage',
                [
                    'label' => __($overviewPage->getName()),
                    'title' => __($overviewPage->getName()),
                    'link' => $this->urlInterface->getUrl($overviewPage->getUrlPath())
                ]
            );
        }

        /** @phpstan-ignore-next-line */
        $breadcrumbsBlock->addCrumb(
            'landingpage',
            [
                'label' => __($landingPage->getName()),
                'title' => __($landingPage->getName()),
            ]
        );
    }
}
