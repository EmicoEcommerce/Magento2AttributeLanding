<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Emico\AttributeLanding\Api\Data\PageSearchResultsInterfaceFactory;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage as ResourcePage;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage\CollectionFactory as PageCollectionFactory;
use Emico\AttributeLanding\Api\Data\OverviewPageInterfaceFactory;

class OverviewPageRepository implements OverviewPageRepositoryInterface
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
     * @var PageCollectionFactory
     */
    protected $pageCollectionFactory;

    /**
     * @var OverviewPageInterfaceFactory
     */
    protected $dataPageFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ResourcePage $resource
     * @param OverviewPageInterfaceFactory $dataPageFactory
     * @param PageCollectionFactory $pageCollectionFactory
     * @param PageSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ResourcePage $resource,
        OverviewPageInterfaceFactory $dataPageFactory,
        PageCollectionFactory $pageCollectionFactory,
        PageSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource = $resource;
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataPageFactory = $dataPageFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param OverviewPageInterface $page
     * @return OverviewPageInterface
     * @throws CouldNotSaveException
     */
    public function save(OverviewPageInterface $page): OverviewPageInterface
    {
        try {
            /** @var OverviewPage $page */
            $parentOverviewPage = $this->dataPageFactory->create();
            $parentOverviewPage->setData($page->getOverviewPageDataWithoutStore());

            $this->resource->save($parentOverviewPage); // @phpstan-ignore-line
            $page->setPageId($parentOverviewPage->getPageId());
            $this->resource->saveOverviewPageStoreData($page);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the page: %1',
                    $exception->getMessage()
                )
            );
        }

        /** @phpstan-ignore-next-line */
        return $page;
    }

    /**
     * @param int $pageId
     * @return OverviewPageInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $pageId): OverviewPageInterface
    {
        $page = $this->dataPageFactory->create();
        /** @var LandingPage $page */
        $this->resource->load($page, $pageId);
        if (!$page->getPageId()) {
            throw new NoSuchEntityException(__('Page with id "%d" does not exist.', $pageId));
        }

        /** @phpstan-ignore-next-line */
        return $page;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $collection = $this->pageCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        /** @phpstan-ignore-next-line */
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param OverviewPageInterface $page
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(OverviewPageInterface $page): bool
    {
        try {
            /** @var LandingPage $page */
            $this->resource->delete($page); // @phpstan-ignore-line
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the Page: %1',
                    $exception->getMessage()
                )
            );
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
     * @return OverviewPageInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function findAllActive(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(OverviewPageInterface::ACTIVE, 1)
            ->addFilter(OverviewPageInterface::STORE_ID, [$storeId, 0], 'in')
            ->create();

        $result = $this->getList($searchCriteria);
        /** @phpstan-ignore-next-line */
        return $result->getItems();
    }

    /**
     * @param LandingPageInterface $landingPage
     * @return OverviewPageInterface
     * @throws NoSuchEntityException
     */
    public function getByLandingPage(LandingPageInterface $landingPage): OverviewPageInterface
    {
        /** @phpstan-ignore-next-line */
        if ($landingPage->getOverviewPageId() === null) {
            throw new NoSuchEntityException(__('The landingpage does not have a overview page linked'));
        }

        return $this->getById($landingPage->getOverviewPageId());
    }

    /**
     * @param int $pageId
     * @param int $storeId
     * @return OverviewPageInterface
     * @throws NoSuchEntityException
     */
    public function getByIdWithStore(int $pageId, int $storeId): OverviewPageInterface
    {
        $overviewPage = $this->getById($pageId);

        $storeData = $this->resource->getOverviewPageStoreData($pageId, $storeId);

        if (!empty($storeData)) {
            unset($storeData['id']);
            $overviewPage->setData($storeData);
        } else {
            $defaultData = $this->resource->getOverviewPageStoreData($pageId, 0);
            if (!empty($defaultData)) {
                unset($defaultData['id']);
                $overviewPage->setData($defaultData);
            }

            $overviewPage->setData(LandingPageInterface::STORE_ID, $storeId);
        }

        return $overviewPage;
    }

    /**
     * @param int $pageId
     * @return OverviewPageInterface[]
     */
    public function getAllPagesById(int $pageId): array
    {
        $storeData = $this->resource->getAllOverviewPageStoreData($pageId);
        $pages = [];

        foreach ($storeData as $data) {
            $page = $this->dataPageFactory->create();
            $page->setData($data);
            $pages[] = $page;
        }

        return $pages;
    }

    /**
     * @param OverviewPageInterface $page
     * @return void
     * @throws CouldNotSaveException
     */
    public function saveOverviewPageStoreData(OverviewPageInterface $page): void
    {
        try {
            $this->resource->saveOverviewPageStoreData($page);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the overview page store data: %1',
                    $exception->getMessage()
                )
            );
        }
    }
}
