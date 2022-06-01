<?php

namespace Emico\AttributeLanding\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();


        $tableName = 'catalog_category_entity';
        $columnName = 'row_id';
        if ($connection->tableColumnExists($tableName, $columnName)) {
            //adobe commerce version
            $connection->addForeignKey(
                'EMICO_ATTRLANDING_PAGE_CTGR_ID_CAT_CTGR_ENTT_ENTT_ID',
                'emico_attributelanding_page',
                'category_id',
                $tableName,
                $columnName,
                'CASCADE',
            );

            if ($connection->tableColumnExists($tableName, 'entity_id')) {
            //open source version
            $connection->addForeignKey(
                'EMICO_ATTRLANDING_PAGE_CTGR_ID_CAT_CTGR_ENTT_ENTT_ID',
                'emico_attributelanding_page',
                'category_id',
                $tableName,
                'entity_id',
                'CASCADE',
            );
        }

        $installer->endSetup();
    }
}

