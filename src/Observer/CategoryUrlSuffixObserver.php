<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2020.
 */

namespace Emico\AttributeLanding\Observer;

use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Model\LandingPage;
use Magento\Catalog\Model\System\Config\Backend\Catalog\Url\Rewrite\Suffix;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Emico\AttributeLanding\Model\UrlRewriteService;

/**
 * Update landingpage url rewrites if category url suffix changes
 *
 * Class CategoryUrlSuffixObserver
 * @package Emico\AttributeLanding\Observer
 */
class CategoryUrlSuffixObserver implements ObserverInterface
{
    /**
     * @var UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var LandingPageRepositoryInterface
     */
    protected $landingPageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var UrlRewriteService
     */
    protected $rewriteService;

    /**
     * CategoryUrlSuffixObserver constructor.
     * @param UrlFinderInterface $urlFinder
     * @param LandingPageRepositoryInterface $landingPageRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param UrlRewriteService $rewriteService
     */
    public function __construct(
        UrlFinderInterface $urlFinder,
        LandingPageRepositoryInterface $landingPageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlRewriteService $rewriteService
    ) {
        $this->urlFinder = $urlFinder;
        $this->landingPageRepository = $landingPageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->rewriteService = $rewriteService;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $dataObject = $observer->getEvent()->getData('data_object');

        if (!$dataObject instanceof Suffix) {
            return;
        }

        $shouldUpdateRewrites = $dataObject->isValueChanged()
            && $dataObject->getPath() === CategoryUrlPathGenerator::XML_PATH_CATEGORY_URL_SUFFIX;

        if (!$shouldUpdateRewrites) {
            return;
        }

        $newSuffix = (string)$dataObject->getValue();

        $this->updateLandingPageRewrites($newSuffix);
    }

    /**
     * @param string $newSuffix
     * @see \Emico\AttributeLanding\Observer\UrlRewriteGenerateObserver::execute()
     */
    protected function updateLandingPageRewrites(string $newSuffix = '')
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
            $this->rewriteService->generateRewrite($page, $newSuffix);
        }
    }
}
