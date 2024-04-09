<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2019.
 */

namespace Emico\AttributeLanding\Controller\LandingPage;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Model\FilterApplier\FilterApplierInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class View extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * @var FilterApplierInterface
     */
    private $filterApplier;

    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LandingPageContext $landingPageContext
     * @param Registry $registry
     * @param LandingPageRepositoryInterface $landingPageRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param FilterApplierInterface $filterApplier
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LandingPageContext $landingPageContext,
        Registry $registry,
        LandingPageRepositoryInterface $landingPageRepository,
        CategoryRepositoryInterface $categoryRepository,
        FilterApplierInterface $filterApplier
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->landingPageRepository = $landingPageRepository;
        $this->categoryRepository = $categoryRepository;
        $this->filterApplier = $filterApplier;
        parent::__construct($context);
        $this->landingPageContext = $landingPageContext;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): ResultInterface
    {
        $pageId = $this->getRequest()->getParam('id');
        $landingPage = $this->landingPageRepository->getById($pageId);

        if (!$landingPage->isActive()) {
            throw new NotFoundException(__('Page not active'));
        }

        $this->landingPageContext->setLandingPage($landingPage);
        $this->setCategoryInRegistry($landingPage);
        $this->filterApplier->applyFilters($landingPage);
        return $this->resultPageFactory->create();
    }

    /**
     * @param LandingPageInterface $page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function setCategoryInRegistry(LandingPageInterface $page)
    {
        $categoryId = $page->getCategoryId();
        if (!$categoryId) {
            return;
        }

        $category = $this->categoryRepository->get($categoryId);
        $this->coreRegistry->register('current_category', $category);
    }
}
