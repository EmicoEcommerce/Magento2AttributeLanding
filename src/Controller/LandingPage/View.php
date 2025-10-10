<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

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
use Magento\Store\Model\StoreManagerInterface;

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
        FilterApplierInterface $filterApplier,
        private readonly StoreManagerInterface $storeManager
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
        $pageId = (int)$this->getRequest()->getParam('id');
        $storeId = (int)$this->storeManager->getStore()->getId();
        $landingPage = $this->landingPageRepository->getByIdWithStore($pageId, $storeId);

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
     * @return void
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
