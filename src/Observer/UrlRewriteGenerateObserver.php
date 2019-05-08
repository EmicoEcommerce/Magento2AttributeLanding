<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Observer;


use Emico\AttributeLanding\Api\Data\StoreSelectableInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManager;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Model\UrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;

class UrlRewriteGenerateObserver implements ObserverInterface
{
    /**
     * @var UrlPersistInterface
     */
    protected $urlPersist;

    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @param UrlPersistInterface $urlPersist
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param StoreManager $storeManager
     */
    public function __construct(UrlPersistInterface $urlPersist, UrlRewriteFactory $urlRewriteFactory, StoreManager $storeManager)
    {
        $this->urlPersist = $urlPersist;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $page = $observer->getEvent()->getData('object');
        if (!$page instanceof UrlRewriteGeneratorInterface) {
            return;
        }

        $urlRewritesToPersist = [];

        foreach ($this->getActiveStoreIds($page) as $storeId) {
            /** @var UrlRewrite $urlRewrite */
            $urlRewrite = $this->urlRewriteFactory->create();
            $urlRewrite
                ->setEntityType($page->getUrlRewriteEntityType())
                ->setEntityId($page->getUrlRewriteEntityId())
                ->setRequestPath($page->getUrlRewriteRequestPath())
                ->setTargetPath($page->getUrlRewriteTargetPath())
                ->setStoreId($storeId);
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
                function(StoreInterface $store) {
                    return $store->getId();
                },
                $this->storeManager->getStores()
            );
        }

        return $landingPage->getStoreIds();
    }
}