<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterfaceFactory;
use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\Data\PageSearchResultsInterface;
use Emico\AttributeLanding\Api\Data\PageSearchResultsInterfaceFactory;
use Emico\AttributeLanding\Model\LandingPageRepository;
use Emico\AttributeLanding\Model\ResourceModel\Page as ResourcePage;
use Emico\AttributeLanding\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;
use Emico\AttributeLanding\Ui\Component\Product\Form\Categories\Options;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mockery;
use Mockery\MockInterface;
use Tweakwise\Test\Support\UnitTester;

class LandingPageRepositoryTest extends Unit
{
    protected UnitTester $tester;

    private ResourcePage|MockInterface $resource;
    private LandingPageInterfaceFactory|MockInterface $dataPageFactory;
    private PageCollectionFactory|MockInterface $pageCollectionFactory;
    private PageSearchResultsInterfaceFactory|MockInterface $searchResultsFactory;
    private CollectionProcessorInterface|MockInterface $collectionProcessor;
    private JoinProcessorInterface|MockInterface $extensionAttributesJoinProcessor;
    private SearchCriteriaBuilder|MockInterface $searchCriteriaBuilder;
    private StoreManagerInterface|MockInterface $storeManager;
    private StoreInterface|MockInterface $store;
    private Options|MockInterface $options;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = Mockery::mock(ResourcePage::class);
        $this->dataPageFactory = Mockery::mock(LandingPageInterfaceFactory::class);
        $this->pageCollectionFactory = Mockery::mock(PageCollectionFactory::class);
        $this->searchResultsFactory = Mockery::mock(PageSearchResultsInterfaceFactory::class);
        $this->collectionProcessor = Mockery::mock(CollectionProcessorInterface::class);
        $this->extensionAttributesJoinProcessor = Mockery::mock(JoinProcessorInterface::class);
        $this->searchCriteriaBuilder = Mockery::mock(SearchCriteriaBuilder::class);
        $this->storeManager = Mockery::mock(StoreManagerInterface::class);
        $this->store = Mockery::mock(StoreInterface::class);
        $this->options = Mockery::mock(Options::class);

        $this->storeManager->shouldReceive('getStore')->andReturn($this->store);

        $searchCriteria = Mockery::mock(SearchCriteriaInterface::class);
        $this->searchCriteriaBuilder->shouldReceive('addFilter')->andReturnSelf();
        $this->searchCriteriaBuilder->shouldReceive('create')->andReturn($searchCriteria);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testFindAllByOverviewPageCurrentStoreItemWinsOverDefaultStoreItem(): void
    {
        $this->store->shouldReceive('getId')->andReturn(2);

        $defaultItem = $this->createLandingPage(10, 0);
        $currentStoreItem = $this->createLandingPage(10, 2);
        $otherPageItem = $this->createLandingPage(20, 0);

        $subject = $this->createSubject([$defaultItem, $currentStoreItem, $otherPageItem]);

        $result = array_values($subject->findAllByOverviewPage($this->createOverviewPage()));

        $this->assertSame([$currentStoreItem, $otherPageItem], $result);
    }

    public function testFindAllByOverviewPageKeepsCurrentStoreItemRegardlessOfResultOrder(): void
    {
        $this->store->shouldReceive('getId')->andReturn(2);

        $currentStoreItem = $this->createLandingPage(10, 2);
        $defaultItem = $this->createLandingPage(10, 0);

        $subject = $this->createSubject([$currentStoreItem, $defaultItem]);

        $result = array_values($subject->findAllByOverviewPage($this->createOverviewPage()));

        $this->assertSame([$currentStoreItem], $result);
    }

    private function createOverviewPage(): OverviewPageInterface|MockInterface
    {
        $overviewPage = Mockery::mock(OverviewPageInterface::class);
        $overviewPage->shouldReceive('getPageId')->andReturn(5);

        return $overviewPage;
    }

    private function createLandingPage(int $pageId, int $storeId): LandingPageInterface|MockInterface
    {
        $landingPage = Mockery::mock(LandingPageInterface::class);
        $landingPage->shouldReceive('getPageId')->andReturn($pageId);
        $landingPage->shouldReceive('getStoreId')->andReturn($storeId);

        return $landingPage;
    }

    /**
     * @param LandingPageInterface[] $items
     */
    private function createSubject(array $items): LandingPageRepository|MockInterface
    {
        $searchResults = Mockery::mock(PageSearchResultsInterface::class);
        $searchResults->shouldReceive('getItems')->andReturn($items);

        $subject = Mockery::mock(
            LandingPageRepository::class . '[getList]',
            [
                $this->resource,
                $this->dataPageFactory,
                $this->pageCollectionFactory,
                $this->searchResultsFactory,
                $this->collectionProcessor,
                $this->extensionAttributesJoinProcessor,
                $this->searchCriteriaBuilder,
                $this->storeManager,
                $this->options,
            ]
        );
        $subject->shouldReceive('getList')->andReturn($searchResults);

        return $subject;
    }
}
