<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Observer;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class UrlRewriteRemoveObserver implements ObserverInterface
{
    /**
     * @var UrlPersistInterface
     */
    protected $urlPersist;

    /**
     * @param UrlPersistInterface $urlPersist
     */
    public function __construct(UrlPersistInterface $urlPersist)
    {
        $this->urlPersist = $urlPersist;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        $page = $observer->getEvent()->getData('object');
        if (!$page instanceof UrlRewriteGeneratorInterface || !$page->getPageId()) {
            return;
        }

        $this->urlPersist->deleteByData(
            [
                UrlRewrite::ENTITY_ID => $page->getUrlRewriteEntityId(),
                UrlRewrite::ENTITY_TYPE => $page->getUrlRewriteEntityType(),
            ]
        );
    }
}
