<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2020.
 */

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class UrlRewriteService
 * @package Emico\AttributeLanding\Model
 */
class UrlRewriteService
{
    /**
     * @var UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var UrlPersistInterface
     */
    protected $urlPersist;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var LandingPageRepositoryInterface
     */
    protected $landingPageRepository;

    /**
     * UrlRewriteService constructor.
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param StoreManagerInterface $storeManager
     * @param UrlPersistInterface $urlPersist
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param UrlFinderInterface $urlFinder
     * @param LandingPageRepositoryInterface $landingPageRepository
     */
    public function __construct(
        UrlRewriteFactory $urlRewriteFactory,
        StoreManagerInterface $storeManager,
        UrlPersistInterface $urlPersist,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlFinderInterface $urlFinder,
        LandingPageRepositoryInterface $landingPageRepository
    ) {
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;
        $this->urlPersist = $urlPersist;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlFinder = $urlFinder;
        $this->landingPageRepository = $landingPageRepository;
    }

    /**
     * @param string $newSuffix
     * @see \Emico\AttributeLanding\Observer\UrlRewriteGenerateObserver::execute()
     */
    public function updateLandingPageRewrites(string $newSuffix = '')
    {
        $filter = [
            UrlRewrite::ENTITY_TYPE => 'landingpage',
        ];

        $landingPageRewrites = $this->urlFinder->findAllByData($filter);
        $landingPageIds = array_map(
            static function (UrlRewrite $rewrite) {
                return $rewrite->getEntityId();
            },
            $landingPageRewrites
        );

        $searchCriteria = $this->searchCriteriaBuilder->addFilter(
            LandingPage::PAGE_ID,
            $landingPageIds,
            'in'
        )->create();

        $landingPages = $this->landingPageRepository->getList($searchCriteria);
        foreach ($landingPages->getItems() as $page) {
            $this->generateRewrite($page, $newSuffix);
        }
    }

    /**
     * @param LandingPage $page
     * @param string|null $suffix
     */
    public function generateRewrite(LandingPage $page, string $suffix = null)
    {
        $urlRewritesToPersist = [];

        foreach ($this->getActiveStoreIds($page) as $storeId) {
            /** @var UrlRewrite $urlRewrite */
            $urlRewrite = $this->urlRewriteFactory->create();
            $urlRewrite
                ->setEntityType($page->getUrlRewriteEntityType())
                ->setEntityId($page->getUrlRewriteEntityId())
                ->setTargetPath($page->getUrlRewriteTargetPath())
                ->setStoreId($storeId);

            $requestPath = ($suffix === null)
                ? $page->getUrlRewriteRequestPath()
                : $page->getUrlPath() . $suffix;

            $urlRewrite->setRequestPath($requestPath);
            $urlRewritesToPersist[] = $urlRewrite;
        }

        $this->urlPersist->replace($urlRewritesToPersist);
    }

    /**
     * @param UrlRewriteGeneratorInterface $landingPage
     * @return int[]
     */
    protected function getActiveStoreIds(UrlRewriteGeneratorInterface $landingPage): array
    {
        if (\in_array('0', $landingPage->getStoreIds(), false) !== false) {
            return array_map(
                static function (StoreInterface $store) {
                    return $store->getId();
                },
                $this->storeManager->getStores()
            );
        }

        return $landingPage->getStoreIds();
    }
}
