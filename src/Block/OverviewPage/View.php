<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Block\OverviewPage;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Emico\AttributeLanding\Model\Page\ImageUploader;
use Magento\Theme\Block\Html\Breadcrumbs;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Psr\Log\LoggerInterface;

class View extends Template
{
    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var \Magento\Framework\Filter\Template
     */
    private $pageFilter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * View constructor.
     * @param Template\Context $context
     * @param LandingPageContext $landingPageContext
     * @param LandingPageRepositoryInterface $landingPageRepository
     * @param ImageUploader $imageUploader
     * @param FilterProvider $filterProvider
     * @param LoggerInterface $logger
     */
    public function __construct(
        Template\Context $context,
        LandingPageContext $landingPageContext,
        LandingPageRepositoryInterface $landingPageRepository,
        ImageUploader $imageUploader,
        FilterProvider $filterProvider,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->landingPageContext = $landingPageContext;
        $this->landingPageRepository = $landingPageRepository;
        $this->imageUploader = $imageUploader;
        $this->pageFilter = $filterProvider->getPageFilter();
        $this->logger = $logger;
    }

    /**
     * @return array|LandingPageInterface[]
     */
    public function getLandingPages(): array
    {
        $overviewPage = $this->getOverviewPage();
        if ($overviewPage === null) {
            return [];
        }

        return $this->landingPageRepository->findAllByOverviewPage($overviewPage);
    }

    /**
     * @return OverviewPageInterface
     */
    public function getOverviewPage(): OverviewPageInterface
    {
        return $this->landingPageContext->getOverviewPage();
    }

    /**
     * @param LandingPageInterface $landingPage
     * @return string|null
     */
    public function getLandingPageImage(LandingPageInterface $landingPage): ?string
    {
        $image = $landingPage->getOverviewPageImage();
        if ($image === null) {
            return null;
        }

        return $this->imageUploader->getMediaUrl($image);
    }

    /**
     * @return string
     */
    public function getContentFirst(): string
    {
        return $this->getFilteredContent($this->getOverviewPage()->getContentFirst() ?? '');
    }

    /**
     * @return string
     */
    public function getContentLast(): string
    {
        return $this->getFilteredContent($this->getOverviewPage()->getContentLast() ?? '');
    }

    /**
     * @param string $content
     * @return string
     */
    protected function getFilteredContent(string $content): string
    {
        try {
            return $this->pageFilter->filter($content);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            return '';
        }
    }

    /**
     * Preparing layout
     *
     * @return View
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    protected function _prepareLayout(): View
    {
        if ($this->landingPageContext->getOverviewPage() === null) {
            return parent::_prepareLayout();
        }

        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        if (!$breadcrumbsBlock instanceof Breadcrumbs) {
            return parent::_prepareLayout();
        }

        $breadcrumbsBlock->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $this->_storeManager->getStore()->getBaseUrl()
            ]
        );

        $overviewPage = $this->getOverviewPage();
        $breadcrumbsBlock->addCrumb(
            'overviewpage',
            [
                'label' => __($overviewPage->getName()),
                'title' => __($overviewPage->getName()),
                'link' => $overviewPage->getUrlPath()
            ]
        );

        return parent::_prepareLayout();
    }
}
