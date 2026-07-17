<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model;

use Emico\AttributeLanding\Api\Data\FilterInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Model\UrlFinder;
use Emico\CodeCept\Test\Unit;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManager;
use Mockery;
use Mockery\MockInterface;
use Tweakwise\Test\Support\UnitTester;

class UrlFinderTest extends Unit
{
    protected UnitTester $tester;

    private LandingPageRepositoryInterface|MockInterface $landingPageRepository;
    private CacheInterface|MockInterface $cache;
    private SerializerInterface|MockInterface $serializer;
    private SearchCriteriaBuilder|MockInterface $searchCriteriaBuilder;
    private StoreManager|MockInterface $storeManager;
    private StoreInterface|MockInterface $store;

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

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testFindUrlByFiltersBuildsLookupAndSkipsInvalidLandingPageFilters(): void
    {
        $searchCriteria = Mockery::mock(SearchCriteriaInterface::class);
        $categoryId = 42;
        $matchingFilter = $this->createFilter('Color', 'Green');
        $matchingHash = $this->createHash([$matchingFilter], $categoryId);

        $invalidLandingPage = Mockery::mock(LandingPageInterface::class);
        $invalidLandingPage->shouldReceive('getFilters')->andThrow(new Exception('Cannot unserialize filters'));

        $validLandingPage = Mockery::mock(LandingPageInterface::class);
        $validLandingPage->shouldReceive('getFilters')->andReturn([$matchingFilter]);
        $validLandingPage->shouldReceive('getCategoryId')->andReturn($categoryId);
        $validLandingPage->shouldReceive('getData')->with('store_id')->andReturn(1);
        $validLandingPage->shouldReceive('getUrlRewriteRequestPath')->andReturn('landing/color-green');

        $resultSet = new class ([$invalidLandingPage, $validLandingPage]) {
            public function __construct(private array $items)
            {
            }

            public function getItems(): array
            {
                return $this->items;
            }
        };

        $this->cache->shouldReceive('load')->with(UrlFinder::CACHE_KEY)->andReturn(false);
        $this->searchCriteriaBuilder->shouldReceive('addFilter')->andReturnSelf();
        $this->searchCriteriaBuilder->shouldReceive('create')->andReturn($searchCriteria);
        $this->landingPageRepository->shouldReceive('getList')->with($searchCriteria)->andReturn($resultSet);

        $this->serializer->shouldReceive('serialize')->once()->with([
            1 => [
                $matchingHash => 'landing/color-green',
            ],
        ])->andReturn('serialized-lookup');
        $this->cache->shouldReceive('save')->with('serialized-lookup', UrlFinder::CACHE_KEY)->andReturnTrue();

        $subject = $this->createSubject();
        $result = $subject->findUrlByFilters([$matchingFilter], $categoryId);

        $this->assertSame('https://store.test/landing/color-green', $result);
    }

    private function createSubject(): UrlFinder
    {
        return new UrlFinder(
            $this->landingPageRepository,
            $this->cache,
            $this->serializer,
            $this->searchCriteriaBuilder,
            $this->storeManager
        );
    }

    private function createFilter(string $facet, string $value): FilterInterface|MockInterface
    {
        $filter = Mockery::mock(FilterInterface::class);
        $filter->shouldReceive('getFacet')->andReturn($facet);
        $filter->shouldReceive('getValue')->andReturn($value);

        return $filter;
    }

    private function createHash(array $filters, ?int $categoryId): string
    {
        usort($filters, static function (FilterInterface $filterA, FilterInterface $filterB): int {
            if ($filterA->getFacet() === $filterB->getFacet()) {
                return $filterA->getValue() > $filterB->getValue() ? 1 : -1;
            }

            return $filterA->getFacet() > $filterB->getFacet() ? 1 : -1;
        });

        $hashParts = array_merge(
            ['category|' . $categoryId],
            array_map(
                static fn (FilterInterface $filter): string => strtolower($filter->getFacet() . '|' . $filter->getValue()),
                $filters
            )
        );

        return md5(implode('%%', $hashParts));
    }
}
