<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Ui\Component\Product\Form\Categories\Options;
use Magento\Catalog\Model\CategoryManagement;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Emico\AttributeLanding\Api\Data\PageSearchResultsInterfaceFactory;
use Emico\AttributeLanding\Model\ResourceModel\Page as ResourcePage;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Emico\AttributeLanding\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    /**
     * @var ResourcePage
     */
    protected $resource;

    /**
     * @var PageSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var PageCollectionFactory
     */
    protected $pageCollectionFactory;

    /**
     * @var LandingPageInterfaceFactory
     */
    protected $dataPageFactory;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Options
     */
    private Options $options;

    /**
     * @param ResourcePage $resource
     * @param LandingPageInterfaceFactory $dataPageFactory
     * @param PageCollectionFactory $pageCollectionFactory
     * @param PageSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Options $options
     */
    public function __construct(
        ResourcePage $resource,
        LandingPageInterfaceFactory $dataPageFactory,
        PageCollectionFactory $pageCollectionFactory,
        PageSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager,
        Options $options
    ) {
        $this->resource = $resource;
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataPageFactory = $dataPageFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function save(LandingPageInterface $page): LandingPageInterface
    {
        try {
            $tree = $this->options->getCategoriesTree();
            $rootCategories = [];

            //set hide filters for root categories
            if (!empty($tree)) {
                foreach ($tree as $rootCategory) {
                    $rootCategories[] = $rootCategory['value'];
                }
            }

            if (in_array($page->getData('category_id'), $rootCategories)) {
                $page->setData('hide_selected_filters', "1");
            }

            /** @var LandingPage $page */
            $this->resource->save($page);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the page: %1',
                $exception->getMessage()
            ));
        }
        return $page;
    }

    /**
     * @param int $pageId
     * @return LandingPageInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $pageId): LandingPageInterface
    {
        $page = $this->dataPageFactory->create();
        /** @var LandingPage $page */
        $this->resource->load($page, $pageId);
        if (!$page->getPageId()) {
            throw new NoSuchEntityException(__('Page with id "%d" does not exist.', $pageId));
        }
        return $page;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $collection = $this->pageCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            LandingPageInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(LandingPageInterface $page): bool
    {
        try {
            /** @var LandingPage $page */
            $this->resource->delete($page);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Page: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $pageId): bool
    {
        return $this->delete($this->getById($pageId));
    }

    /**
     * @return LandingPageInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function findAllActive(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(LandingPageInterface::ACTIVE, 1)
            ->create();

        $result = $this->getList($searchCriteria);
        return $result->getItems();
    }

    /**
     * @param OverviewPageInterface $overviewPage
     * @return LandingPageInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function findAllByOverviewPage(OverviewPageInterface $overviewPage): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(LandingPageInterface::OVERVIEW_PAGE_ID, $overviewPage->getPageId())
            ->addFilter(LandingPageInterface::ACTIVE, 1)
            ->create();

        $result = $this->getList($searchCriteria);
        return $result->getItems();
    }
}
