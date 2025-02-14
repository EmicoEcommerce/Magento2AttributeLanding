<?php

namespace Emico\AttributeLanding\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class ConvertLandingpageEntries implements DataPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup,
        private StoreManagerInterface $storeManager
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->storeManager = $storeManager;
    }

    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        $connection = $this->moduleDataSetup->getConnection();
        $landingPageTable = $this->moduleDataSetup->getTable('emico_attributelanding_page');
        $landingPageStoreTable = $this->moduleDataSetup->getTable('emico_attributelanding_page_store');
        $stores = $this->storeManager->getStores();

        $select = $connection->select()->from($landingPageTable);
        $landingPages = $connection->fetchAll($select);

        foreach ($landingPages as $landingPage) {
            $storeIds = explode(',', $landingPage['store_ids']);
            if (in_array('0', $storeIds)) {
                foreach ($stores as $store) {
                    $this->insertLandingPageStore($connection, $landingPageStoreTable, $landingPage, $store->getId());
                }
            } else {
                foreach ($storeIds as $storeId) {
                    $this->insertLandingPageStore($connection, $landingPageStoreTable, $landingPage, $storeId);
                }
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    private function insertLandingPageStore(AdapterInterface $connection, $table, $landingPage, $storeId): void
    {
        $data = [
            'page_id' => $landingPage['page_id'],
            'store_id' => $storeId,
        ];

        $fields = [
            'active', 'url_path', 'category_id', 'heading', 'header_image', 'meta_title',
            'meta_keywords', 'meta_description', 'content_first', 'content_last',
            'filter_attributes', 'tweakwise_filter_template', 'is_filter_link_allowed',
            'canonical_url', 'hide_selected_filters', 'tweakwise_sort_template',
            'tweakwise_builder_template'
        ];

        foreach ($fields as $field) {
            if (isset($landingPage[$field])) {
                $data[$field] = $landingPage[$field];
            }
        }

        $connection->insert($table, $data);
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
