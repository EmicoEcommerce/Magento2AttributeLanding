<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2020.
 */

namespace Emico\AttributeLanding\Model\System\Config;

use Emico\AttributeLanding\Model\UrlRewriteService;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class AppendUrlSuffix extends Value
{
    /**
     * @var UrlRewriteService
     */
    protected $rewriteService;

    /**
     * AppendUrlSuffix constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param UrlRewriteService $rewriteService
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        UrlRewriteService $rewriteService,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );

        $this->rewriteService = $rewriteService;
    }

    /**
     * @return Value
     */
    public function afterSave()
    {
        if (!$this->isValueChanged()) {
            return parent::afterSave();
        }

        $newValue = $this->getValue();

        if (!$newValue) {
            // This indicates that url suffix should not be appended to the landingpage url
            $this->rewriteService->updateLandingPageRewrites('');
        } else {
            $suffixValue = $this->_config
                ->getValue(CategoryUrlPathGenerator::XML_PATH_CATEGORY_URL_SUFFIX);
            $this->rewriteService->updateLandingPageRewrites($suffixValue);
        }

        return parent::afterSave();
    }
}
