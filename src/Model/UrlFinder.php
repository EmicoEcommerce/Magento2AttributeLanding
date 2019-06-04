<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Model;


use Emico\AttributeLanding\Api\Data\FilterInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\Tweakwise\Model\Catalog\Layer\Filter\Item;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;

class UrlFinder
{
    const CACHE_KEY = 'attributelanding.lookup.filter_url';

    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * @var array
     */
    private $landingPageLookup;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * UrlFinder constructor.
     * @param LandingPageRepositoryInterface $landingPageRepository
     * @param CacheInterface $cache
     * @param SerializerInterface $serializer
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        LandingPageRepositoryInterface $landingPageRepository,
        CacheInterface $cache,
        SerializerInterface $serializer,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->landingPageRepository = $landingPageRepository;
        $this->cache = $cache;
        $this->serializer = $serializer;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param array $filterItems
     * @param int|null $categoryId
     * @return string|null
     */
    public function findUrlByFilters(array $filterItems, int $categoryId = null)
    {
        $filters = array_map(
            function (Item $item) {
                return new Filter(
                    $item->getFilter()->getUrlKey(),
                    $item->getAttribute()->getTitle()
                );
            },
            $filterItems
        );
        $filterHash = $this->createHashForFilters($filters, $categoryId);

        if ($this->landingPageLookup === null) {
            $this->landingPageLookup = $this->loadPageLookupArray();
        }

        if (!isset($this->landingPageLookup[$filterHash])) {
            return null;
        }

        return $this->landingPageLookup[$filterHash];
    }

    /**
     * @param array $filters
     * @param int|null $categoryId
     * @return string
     */
    protected function createHashForFilters(array $filters, int $categoryId = null): string
    {
        usort($filters, [$this, 'sortFilterItems']);

        $hashParts = array_merge(
            ['category|' . $categoryId],
            array_map(
                function(FilterInterface $filter) {
                    return $filter->getFacet() . '|' . $filter->getValue();
                },
                $filters
            )
        );

        return md5(implode('%%', $hashParts));
    }

    /**
     * Load array to lookup landing page URL by a given filter combination (represented by a hash)
     */
    protected function loadPageLookupArray(): array
    {
        $landingPageLookup = $this->cache->load(self::CACHE_KEY);
        if ($landingPageLookup !== false) {
            return $this->serializer->unserialize($landingPageLookup);
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(LandingPageInterface::ACTIVE, 1)
            ->addFilter(LandingPageInterface::FILTER_LINK_ALLOWED, 1)
            ->create();

        $landingPageLookup = [];
        foreach ($this->landingPageRepository->getList($searchCriteria)->getItems() as $landingPage) {
            $hash = $this->createHashForFilters($landingPage->getFilters(), $landingPage->getCategoryId());
            $landingPageLookup[$hash] = $landingPage->getUrlPath();
        }
        $this->cache->save($this->serializer->serialize($landingPageLookup), self::CACHE_KEY);
        return $landingPageLookup;
    }

    /**
     * First sort filter items by facet and then on attribute
     *
     * @param FilterInterface $filterA
     * @param FilterInterface $filterB
     * @return int
     */
    protected function sortFilterItems(FilterInterface $filterA, FilterInterface $filterB): int
    {
        if ($filterA->getFacet() === $filterB->getFacet()) {
            return $filterA->getValue() > $filterB->getValue() ? 1 : -1;
        }

        return $filterA->getFacet() > $filterB->getFacet() ? 1 : -1;
    }
}