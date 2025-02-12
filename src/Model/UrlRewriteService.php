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
     */
    public function generateRewrite(UrlRewriteGeneratorInterface $page, string $suffix = null)
    {
        // This is needed because you want to delete all the existing rewrites
        // before saving the new ones due to same url paths but different store id.
        // When duplicating a page, for example, the url_rewrite of that store gets copied as well and
        // this needs to be removed in order to make it possible
        // to choose same url paths with different store views
        $this->removeExistingUrlRewrites($page);

        $urlRewritesToPersist = [];

        $allPages = $this->landingPageRepository->getAllPagesById($page->getPageId());


        foreach ($allPages as $storePage) {
            if ($storePage->getStoreId() == $page->getStoreId()) {
                $storePage = $page;
            }

            if ($storePage->getStoreId() == 0) {
                $stores = $this->storeManager->getStores();
                foreach ($stores as $store) {
                    if ($store->getId() == $page->getStoreId()) {
                        $storePage = $page;
                    }

                    if (!empty($storePage)) {
                        if (!isset($urlRewritesToPersist[$store->getId()])) {
                            $urlRewrite = $this->createUrlRewrite($storePage, $store->getId(), $suffix);
                            $urlRewritesToPersist[$store->getId()] = $urlRewrite;
                        }
                    }
                }
            } else {
                $urlRewrite = $this->createUrlRewrite($storePage, $storePage->getStoreId(), $suffix);
                $urlRewritesToPersist[$storePage->getStoreId()] = $urlRewrite;
            }
        }
        /** @var UrlRewrite $urlRewrite **/

        $this->urlPersist->replace($urlRewritesToPersist);

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

    private function createUrlRewrite(UrlRewriteGeneratorInterface $page, int $storeId, string $suffix = null): UrlRewrite
    {
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
}
