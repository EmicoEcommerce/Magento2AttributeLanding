<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2020.
 */

namespace Emico\AttributeLanding\Observer;

use Emico\AttributeLanding\Model\Config;
use Magento\Catalog\Model\System\Config\Backend\Catalog\Url\Rewrite\Suffix;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Emico\AttributeLanding\Model\UrlRewriteService;

/**
 * Update landing page url rewrites if category url suffix changes
 *
 * Class CategoryUrlSuffixObserver
 */
class CategoryUrlSuffixObserver implements ObserverInterface
{
    /**
     * @var UrlRewriteService
     */
    protected $rewriteService;

    /**
     * @var Config
     */
    protected $config;

    /**
     * CategoryUrlSuffixObserver constructor.
     * @param UrlRewriteService $rewriteService
     * @param Config $config
     */
    public function __construct(
        UrlRewriteService $rewriteService,
        Config $config
    ) {
        $this->rewriteService = $rewriteService;
        $this->config = $config;
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
            && $dataObject->getPath() === CategoryUrlPathGenerator::XML_PATH_CATEGORY_URL_SUFFIX
            && $this->config->isAppendCategoryUrlSuffix();

        if (!$shouldUpdateRewrites) {
            return;
        }

        $newSuffix = (string)$dataObject->getValue();
        $this->rewriteService->updateLandingPageRewrites($newSuffix);
    }
}
