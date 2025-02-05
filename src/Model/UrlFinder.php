<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\FilterInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManager;

class UrlFinder
{
    public const CACHE_KEY = 'attributelanding.lookup.filter_url';

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
     * @var StoreManager
     */
    private $storeManager;

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
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManager $storeManager
    ) {
        $this->landingPageRepository = $landingPageRepository;
        $this->cache = $cache;
        $this->serializer = $serializer;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Filter[] $filters
     * @param int|null $categoryId
     * @return string|null
     */
    public function findUrlByFilters(array $filters, int $categoryId = null)
    {
        $result = null;
        $filterHash = $this->createHashForFilters($filters, $categoryId);

        if ($this->landingPageLookup === null) {
            $this->landingPageLookup = $this->loadPageLookupArray();
        }

        $storePrefix = $this->storeManager->getStore()->getBaseUrl();

        if (!isset($this->landingPageLookup[$this->storeManager->getStore()->getId()][$filterHash])) {
            //check if hash is set for all stores
            if (isset($this->landingPageLookup[0][$filterHash])) {
                $result = $this->landingPageLookup[0][$filterHash];
            } else {
                return null;
            }
        }

        if (empty($result)) {
            $result = $this->landingPageLookup[$this->storeManager->getStore()->getId()][$filterHash];
        }

        if (strpos($result, 'http') === false) {
            //add store url if link doesn't start with http
            $result = $storePrefix . $result;
        }

        return $result;
    }

    /**
     * @param array $filters
     * @param int|null $categoryId
     * @return string
     *
     * phpcs:disable Magento2.Security.InsecureFunction.FoundWithAlternative
     */
    protected function createHashForFilters(array $filters, int $categoryId = null): string
    {
        usort($filters, [$this, 'sortFilterItems']);

        $hashParts = array_merge(
            ['category|' . $categoryId],
            array_map(
                function (FilterInterface $filter) {
                    return strtolower($filter->getFacet() . '|' . $filter->getValue());
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
        $landingPageStores = [];
        foreach ($this->landingPageRepository->getList($searchCriteria)->getItems() as $landingPage) {
            $hash = $this->createHashForFilters($landingPage->getFilters(), $landingPage->getCategoryId());

            $storeId = $landingPage->getData('store_id');


            $landingPageLookup[$storeId][$hash] = $landingPage->getUrlRewriteRequestPath();
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
