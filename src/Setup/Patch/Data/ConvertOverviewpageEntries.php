<?php

namespace Emico\AttributeLanding\Setup\Patch\Data;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class ConvertOverviewpageEntries implements DataPatchInterface
{
    /**
     * ConvertLandingpageEntries constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly StoreManagerInterface $storeManager
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        $connection = $this->moduleDataSetup->getConnection();
        $overviewPageTable = $this->moduleDataSetup->getTable('emico_attributelanding_overviewpage');
        $overviewPageStoreTable = $this->moduleDataSetup->getTable('emico_attributelanding_overviewpage_store');
        $stores = $this->storeManager->getStores();

        $select = $connection->select()->from($overviewPageTable);
        $overviewPages = $connection->fetchAll($select);

        foreach ($overviewPages as $overviewPage) {
            $storeIds = explode(',', $overviewPage['store_ids']);
            foreach ($storeIds as $storeId) {
                $this->insertOverviewPageStore($connection, $overviewPageStoreTable, $overviewPage, $storeId);
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @param AdapterInterface $connection
     * @param string $table
     * @param array $overviewPage
     * @param int $storeId
     */
    private function insertOverviewPageStore(
        AdapterInterface $connection,
        string $table,
        array $overviewPage,
        int $storeId
    ): void {
        $data = [
            'page_id' => $overviewPage['page_id'],
            'store_id' => $storeId,
        ];

        $fields = [
            OverviewPageInterface::ACTIVE,
            OverviewPageInterface::URL_PATH,
            OverviewPageInterface::HEADING,
            OverviewPageInterface::META_TITLE,
            OverviewPageInterface::META_KEYWORDS,
            OverviewPageInterface::META_DESCRIPTION,
            OverviewPageInterface::CONTENT_FIRST,
            OverviewPageInterface::CONTENT_LAST,
            OverviewPageInterface::NAME,
        ];

        foreach ($fields as $field) {
            if (isset($overviewPage[$field])) {
                $data[$field] = $overviewPage[$field];
            }
        }

        $connection->insert($table, $data);
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
