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
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

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
     * View constructor.
     * @param Template\Context $context
     * @param LandingPageContext $landingPageContext
     * @param LandingPageRepositoryInterface $landingPageRepository
     */
    public function __construct(
        Template\Context $context,
        LandingPageContext $landingPageContext,
        LandingPageRepositoryInterface $landingPageRepository,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->landingPageContext = $landingPageContext;
        $this->landingPageRepository = $landingPageRepository;
        $this->imageUploader = $imageUploader;
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
     * Preparing layout
     *
     * @return \Magento\Catalog\Block\Breadcrumbs
     */
    protected function _prepareLayout()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
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
        }
        return parent::_prepareLayout();
    }
}