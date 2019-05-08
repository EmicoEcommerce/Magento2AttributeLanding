<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2019.
 */

namespace Emico\AttributeLanding\Setup;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $tableName = 'emico_attributelanding_page';
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable($tableName))
                ->addColumn(
                    LandingPageInterface::PAGE_ID,
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ],
                    'ID'
                )->addColumn(
                    LandingPageInterface::ACTIVE,
                    Table::TYPE_BOOLEAN,
                    null,
                    [],
                    'Page Active'
                )->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Creation Time'
                )
                ->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                )->addColumn(
                    LandingPageInterface::URL_PATH,
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false,
                        'unique' => true
                    ],
                    'Url Path'
                )->addColumn(
                    LandingPageInterface::CATEGORY_ID,
                    Table::TYPE_INTEGER,
                    10,
                    [
                        'unsigned' => true
                    ],
                    'Category Id'
                )->addColumn(
                    LandingPageInterface::HEADING,
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'H2 Heading'
                )->addColumn(
                    LandingPageInterface::HEADER_IMAGE,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Header Image'
                )->addColumn(
                    LandingPageInterface::META_TITLE,
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Meta Title'
                )->addColumn(
                    LandingPageInterface::META_KEYWORDS,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Meta Keywords'
                )->addColumn(
                    LandingPageInterface::META_DESCRIPTION,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Meta Description'
                )->addColumn(
                    LandingPageInterface::CONTENT_FIRST,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Content above results'
                )->addColumn(
                    LandingPageInterface::CONTENT_LAST,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Content below results'
                )->addColumn(
                    LandingPageInterface::FILTER_ATTRIBUTES,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'The attribute values defining the page'
                )->addColumn(
                    LandingPageInterface::TWEAKWISE_FILTER_TEMPLATE,
                    Table::TYPE_INTEGER,
                    null,
                    [],
                    'Associated tweakwise filter template of applicable'
                )->addColumn(
                    LandingPageInterface::STORE_IDS,
                    Table::TYPE_TEXT,
                    255,
                    [],
                'Activated for stores'
            );
            $table->addIndex(
                $installer->getIdxName($tableName, [LandingPageInterface::PAGE_ID]),
                [LandingPageInterface::PAGE_ID]
            );
            $table->addIndex(
                $installer->getIdxName($tableName, [LandingPageInterface::URL_PATH]),
                [LandingPageInterface::URL_PATH]
            );

            $categoryTableName = $installer->getTable('catalog_category_entity');
            $table->addForeignKey(
                $installer->getFkName(
                    $tableName,
                    'category_id',
                    $categoryTableName,
                    'entity_id'
                ),
                'category_id',
                $categoryTableName,
                'entity_id',
                Table::ACTION_CASCADE
            );

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}