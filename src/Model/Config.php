<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model;

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
     * @param Store|null $store
     * @param string $path
     * @return mixed|null|string
     */
    protected function getStoreConfig(string $path, Store $store = null)
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
    public function isCrosslinkEnabled(Store $store = null): bool
    {
        return (bool) $this->getStoreConfig('emico_attributelanding/general/allow_crosslink', $store);
    }
}
