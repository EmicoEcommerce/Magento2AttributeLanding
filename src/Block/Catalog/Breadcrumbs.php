<?php

/**
 * @author        Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 * @noinspection  PhpMethodNamingConventionInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Block\Catalog;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Block\Breadcrumbs as CatalogBreadcrumbs;
use Magento\Catalog\Helper\Data;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Adds landing page breadcrumbs
 * We have no way to overwrite category breadcrumbs using events or plugins.
 * So we need to extend the Magento\Catalog\Block\Breadcrumbs breadcrumbs block
 */
class Breadcrumbs extends CatalogBreadcrumbs
{
    /**
     * @param Context            $context
     * @param Data               $catalogData
     * @param LandingPageContext $landingPageContext
     * @param OverviewPageRepositoryInterface $overviewPageRepository
     * @param UrlInterface       $urlInterface
     * @param array              $data
     */
    public function __construct(
        Context $context,
        Data $catalogData,
        private readonly LandingPageContext $landingPageContext,
        private readonly OverviewPageRepositoryInterface $overviewPageRepository,
        private readonly UrlInterface $urlInterface,
        array $data = [],
    ) {
        parent::__construct($context, $catalogData, $data);
    }

    /**
     * @param LandingPageInterface $landingPage
     *
     * @throws LocalizedException
     * @throws NoSuchEntityException
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
                'link' => $this->_storeManager->getStore()->getBaseUrl(),
            ],
        );

        /** @phpstan-ignore-next-line */
        if ($landingPage->getOverviewPageId() !== null) {
            $overviewPage = $this->overviewPageRepository->getById($landingPage->getOverviewPageId()); // @phpstan-ignore-line
            /** @phpstan-ignore-next-line */
            $breadcrumbsBlock->addCrumb(
                'overviewpage',
                [
                    'label' => __($overviewPage->getName()),
                    'title' => __($overviewPage->getName()),
                    'link' => $this->urlInterface->getUrl($overviewPage->getUrlPath()),
                ],
            );
        }

        /** @phpstan-ignore-next-line */
        $breadcrumbsBlock->addCrumb(
            'landingpage',
            [
                'label' => __($landingPage->getName()),
                'title' => __($landingPage->getName()),
            ],
        );
    }

    /**
     * Preparing layout
     *
     * @return CatalogBreadcrumbs
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function _prepareLayout(): CatalogBreadcrumbs
    {
        $landingPage = $this->landingPageContext->getLandingPage();
        /** @phpstan-ignore-next-line */
        if ($landingPage === null) {
            return parent::_prepareLayout();
        }

        $this->addLandingPageBreadCrumbs($landingPage);

        return $this;
    }
}
