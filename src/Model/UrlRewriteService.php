<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2020.
 */

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

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
     * @param UrlRewriteGeneratorInterface $page
     * @param string|null $suffix
     * @throws \Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException|\Exception
     */
    public function generateRewrite(UrlRewriteGeneratorInterface $page, ?string $suffix = null)
    {
        $this->removeExistingUrlRewrites($page);
        $urlRewritesToPersist = [];

        if ($page->getUrlRewriteEntityType() == 'landingpage') {
            $urlRewritesToPersist = $this->generateLandingPageRewrites($page, $suffix);
        } elseif ($page->getUrlRewriteEntityType() == 'landingpage_overview') {
            $urlRewritesToPersist = $this->generateOverviewPageRewrites($page, $suffix);
        }

        $this->urlPersist->replace($urlRewritesToPersist);
    }

    /**
     * @param UrlRewriteGeneratorInterface $page
     * @param string|null $suffix
     * @return array
     */
    private function generateLandingPageRewrites(UrlRewriteGeneratorInterface $page, ?string $suffix = null): array
    {
        $urlRewritesToPersist = [];
        $allPages = $this->landingPageRepository->getAllPagesById($page->getPageId());

        foreach ($allPages as $storePage) {
            if ($storePage->getStoreId() == $page->getStoreId()) {
                $storePage = $page;
            }

            if ($storePage->getStoreId() == 0) {
                $urlRewritesToPersist = $this->generateRewritesForAllStores(
                    $storePage,
                    $page,
                    $suffix,
                    $urlRewritesToPersist
                );
            } else {
                $urlRewrite = $this->createUrlRewrite($storePage, $storePage->getStoreId(), $suffix);
                $urlRewritesToPersist[$storePage->getStoreId()] = $urlRewrite;
            }
        }

        return $urlRewritesToPersist;
    }

    /**
     * @param LandingPageInterface $storePage
     * @param LandingPageInterface $page
     * @param string|null $suffix
     * @param array $urlRewritesToPersist
     * @return array
     */
    private function generateRewritesForAllStores(
        LandingPageInterface $storePage,
        LandingPageInterface $page,
        ?string $suffix,
        array $urlRewritesToPersist
    ): array {
        $stores = $this->storeManager->getStores();

        foreach ($stores as $store) {
            if ($store->getId() == $page->getStoreId()) {
                $storePage = $page;
            }

            if (empty($storePage)) {
                continue;
            }

            if (!isset($urlRewritesToPersist[$store->getId()])) {
                $urlRewrite = $this->createUrlRewrite($storePage, $store->getId(), $suffix);
                $urlRewritesToPersist[$store->getId()] = $urlRewrite;
            }
        }

        return $urlRewritesToPersist;
    }

    /**
     * @param UrlRewriteGeneratorInterface $page
     * @param string|null $suffix
     * @return array
     */
    private function generateOverviewPageRewrites(UrlRewriteGeneratorInterface $page, ?string $suffix = null): array
    {
        $urlRewritesToPersist = [];

        foreach ($this->getActiveStoreIds($page) as $storeId) {
            $urlRewritesToPersist[$storeId] = $this->createUrlRewrite($page, $storeId, $suffix);
        }

        return $urlRewritesToPersist;
    }

    /**
     * @param UrlRewriteGeneratorInterface $page
     * @return void
     */
    protected function removeExistingUrlRewrites(UrlRewriteGeneratorInterface $page): void
    {
        $this->urlPersist->deleteByData(
            [
                UrlRewrite::ENTITY_ID => $page->getUrlRewriteEntityId(),
                UrlRewrite::ENTITY_TYPE => $page->getUrlRewriteEntityType(),
            ]
        );
    }

    private function createUrlRewrite(
        UrlRewriteGeneratorInterface $page,
        int $storeId,
        ?string $suffix = null
    ): UrlRewrite {
        /** @var UrlRewrite $urlRewrite **/
        $urlRewrite = $this->urlRewriteFactory->create();
        $urlRewrite
            ->setEntityType($page->getUrlRewriteEntityType())
            ->setEntityId($page->getUrlRewriteEntityId())
            ->setTargetPath($page->getUrlRewriteTargetPath())
            ->setStoreId($storeId);

        $requestPath = ($suffix === null)
            ? $page->getUrlRewriteRequestPath()
            : $page->getUrlPath() . $suffix;

        $requestPath = trim($requestPath, '/');

        $urlRewrite->setRequestPath($requestPath);

        return $urlRewrite;
    }

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
