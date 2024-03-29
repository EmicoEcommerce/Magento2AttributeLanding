<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Observer;

use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Emico\AttributeLanding\Model\UrlRewriteService;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UrlRewriteGenerateObserver implements ObserverInterface
{
    /**
     * @var UrlRewriteService
     */
    protected $rewriteService;

    /**
     * UrlRewriteGenerateObserver constructor.
     * @param UrlRewriteService $rewriteService
     */
    public function __construct(
        UrlRewriteService $rewriteService
    ) {
        $this->rewriteService = $rewriteService;
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

        $this->rewriteService->generateRewrite($page);
    }
}
