<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2020.
 */

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Model\UrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;

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
     * UrlRewriteService constructor.
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param StoreManagerInterface $storeManager
     * @param UrlPersistInterface $urlPersist
     */
    public function __construct(
        UrlRewriteFactory $urlRewriteFactory,
        StoreManagerInterface $storeManager,
        UrlPersistInterface $urlPersist
    ) {
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;
        $this->urlPersist = $urlPersist;
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
     * @return array
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
