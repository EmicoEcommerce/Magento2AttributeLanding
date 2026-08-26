<?php

declare(strict_types=1);

namespace Emico\AttributeLandingTest\Unit\Model;

use Emico\AttributeLanding\Api\Data\FilterInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\PageSearchResultsInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Model\UrlFinder;
use Emico\AttributeLandingTest\Support\UnitTester;
use Emico\CodeCept\Test\Unit;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManager;
use Mockery;
use Mockery\MockInterface;
use Throwable;

class UrlFinderTest extends Unit
{
    protected UnitTester $tester;
    private LandingPageRepositoryInterface|MockInterface $landingPageRepository;
    private CacheInterface|MockInterface $cache;
    private SerializerInterface|MockInterface $serializer;
    private SearchCriteriaBuilder|MockInterface $searchCriteriaBuilder;
    private StoreManager|MockInterface $storeManager;
    private StoreInterface|MockInterface $store;

    /**
     * @throws NoSuchEntityException
     * @throws Throwable
     * @throws LocalizedException
     */
    public function testFindUrlByFiltersBuildsLookupAndSkipsInvalidLandingPageFilters(): void
    {
        $searchCriteria = Mockery::mock(SearchCriteriaInterface::class);
        $categoryId = 42;
        $matchingFilter = $this->createFilter('Color', 'Green');

        $invalidLandingPage = Mockery::mock(LandingPageInterface::class);
        $invalidLandingPage->shouldReceive('getFilters')->andThrow(new Exception('Cannot unserialize filters'));

        $validLandingPage = Mockery::mock(LandingPageInterface::class);
        $validLandingPage->shouldReceive('getFilters')->andReturn([$matchingFilter]);
        $validLandingPage->shouldReceive('getCategoryId')->andReturn($categoryId);
        $validLandingPage->shouldReceive('getData')->with('store_id')->andReturn(1);
        $validLandingPage->shouldReceive('getUrlRewriteRequestPath')->andReturn('landing/color-green');

        $resultSet = new class ([$invalidLandingPage, $validLandingPage]) implements PageSearchResultsInterface {
            private array $items = [];
            private SearchCriteriaInterface $searchCriteria;
            private int $totalCount = 0;

            public function __construct()
            {
            }

            public function getItems(): array
            {
                return $this->items;
            }

            public function setItems(array $items): self
            {
                $this->items = $items;
                $this->setTotalCount(count($items));

                return $this;
            }

            public function getSearchCriteria(): SearchCriteriaInterface
            {
                return $this->searchCriteria;
            }

            public function setSearchCriteria(SearchCriteriaInterface $searchCriteria): self
            {
                $this->searchCriteria = $searchCriteria;

                return $this;
            }

            public function getTotalCount(): int
            {
                return $this->totalCount;
            }

            public function setTotalCount($totalCount): self
            {
                $this->totalCount = $totalCount;

                return $this;
            }
        };

        $this->cache->shouldReceive('load')->with(UrlFinder::CACHE_KEY)->andReturn(false);
        $this->searchCriteriaBuilder->shouldReceive('addFilter')->andReturnSelf();
        $this->searchCriteriaBuilder->shouldReceive('create')->andReturn($searchCriteria);
        $this->landingPageRepository->shouldReceive('getList')->with($searchCriteria)->andReturn($resultSet);

        $this->serializer->shouldReceive('serialize')->once()->with(
            Mockery::on(
                static function (array $lookup): bool {
                    if (!isset($lookup[1]) || !is_array($lookup[1]) || count($lookup[1]) !== 1) {
                        return false;
                    }

                    return reset($lookup[1]) === 'landing/color-green';
                },
            ),
        )->andReturn('serialized-lookup');
        $this->cache->shouldReceive('save')->with('serialized-lookup', UrlFinder::CACHE_KEY)->andReturnTrue();

        $subject = $this->createSubject();
        $result = $subject->findUrlByFilters([$matchingFilter], $categoryId);

        $this->tester->assertSame('https://store.test/landing/color-green', $result);
    }

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->landingPageRepository = Mockery::mock(LandingPageRepositoryInterface::class);
        $this->cache = Mockery::mock(CacheInterface::class);
        $this->serializer = Mockery::mock(SerializerInterface::class);
        $this->searchCriteriaBuilder = Mockery::mock(SearchCriteriaBuilder::class);
        $this->storeManager = Mockery::mock(StoreManager::class);
        $this->store = Mockery::mock(StoreInterface::class);

        $this->storeManager->shouldReceive('getStore')->andReturn($this->store);
        $this->store->shouldReceive('getBaseUrl')->andReturn('https://store.test/');
        $this->store->shouldReceive('getId')->andReturn(1);
    }

    /**
     * @throws Throwable
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    private function createSubject(): UrlFinder
    {
        return new UrlFinder(
            $this->landingPageRepository,
            $this->cache,
            $this->serializer,
            $this->searchCriteriaBuilder,
            $this->storeManager,
        );
    }

    /**
     * @throws Throwable
     */
    private function createFilter(string $facet, string $value): FilterInterface|MockInterface
    {
        $filter = Mockery::mock(FilterInterface::class);
        $filter->shouldReceive('getFacet')->andReturn($facet);
        $filter->shouldReceive('getValue')->andReturn($value);

        return $filter;
    }
}
