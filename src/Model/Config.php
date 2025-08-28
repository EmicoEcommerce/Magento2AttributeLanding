<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model;

use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    protected $config;

    /**
     * Export constructor.
     *
     * @param ScopeConfigInterface $config
     */
    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $path
     * @param Store|null $store
     * @return mixed|string|null
     */
    protected function getStoreConfig(string $path, ?Store $store = null)
    {
        if ($store) {
            return $store->getConfig($path);
        }

        return $this->config->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param Store|null $store
     * @return bool
     */
    public function isCrossLinkEnabled(?Store $store = null): bool
    {
        return (bool) $this->getStoreConfig('emico_attributelanding/general/allow_crosslink', $store);
    }

    /**
     * @param Store|null $store
     * @return bool
     */
    public function isAppendCategoryUrlSuffix(?Store $store = null): bool
    {
        return (bool) $this->getStoreConfig('emico_attributelanding/general/append_category_url_suffix', $store);
    }

    /**
     * @param Store|null $store
     * @return string
     */
    public function getCategoryUrlSuffix(?Store $store = null)
    {
        return (string) $this->getStoreConfig(CategoryUrlPathGenerator::XML_PATH_CATEGORY_URL_SUFFIX, $store);
    }

    /**
     * @param Store|null $store
     * @return bool
     */
    public function isCanonicalSelfReferencingEnabled(?Store $store = null): bool
    {
        return (bool) $this->config->isSetFlag(
            'emico_attributelanding/general/canonical_self_referencing',
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
