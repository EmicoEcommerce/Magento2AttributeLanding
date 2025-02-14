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
            'active' => $landingPage['active'],
            'url_path' => $landingPage['url_path'],
            'category_id' => $landingPage['category_id'],
            'heading' => $landingPage['heading'],
            'header_image' => $landingPage['header_image'],
            'meta_title' => $landingPage['meta_title'],
            'meta_keywords' => $landingPage['meta_keywords'],
            'meta_description' => $landingPage['meta_description'],
            'content_first' => $landingPage['content_first'],
            'content_last' => $landingPage['content_last'],
            'filter_attributes' => $landingPage['filter_attributes'],
            'tweakwise_filter_template' => $landingPage['tweakwise_filter_template'],
            'is_filter_link_allowed' => $landingPage['is_filter_link_allowed'],
            'canonical_url' => $landingPage['canonical_url'],
            'hide_selected_filters' => $landingPage['hide_selected_filters'],
            'tweakwise_sort_template' => $landingPage['tweakwise_sort_template'],
            'tweakwise_builder_template' => $landingPage['tweakwise_builder_template'],
        ];

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
