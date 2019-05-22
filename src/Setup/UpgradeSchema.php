<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Setup;


use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Define table names
     */
    const LANDINGPAGE_TABLE = 'emico_attributelanding_page';
    const OVERVIEWPAGE_TABLE = 'emico_attributelanding_overviewpage';

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
        $setup->startSetup();
        if (version_compare($context->getVersion(), '0.2.0') < 0) {
            $this->addOverviewPageTable($setup);
            $this->addOverviewPageFieldToPageTable($setup);
        }
        if (version_compare($context->getVersion(), '0.3.0') < 0) {
            $this->addFilterLinkAllowedColumn($setup);
        }
        if (version_compare($context->getVersion(), '0.4.0') < 0) {
            $this->addNameColumn($setup);
        }
        if (version_compare($context->getVersion(), '0.4.1') < 0) {
            $this->addOverviewImageColumn($setup);
        }
        if (version_compare($context->getVersion(), '0.4.2') < 0) {
            $this->addCanonicalUrlColumn($setup);
        }
        if (version_compare($context->getVersion(), '1.0.0') < 0) {
            $this->addHideSelectedFiltersColumn($setup);
        }
        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function addOverviewPageTable(SchemaSetupInterface $setup)
    {
        $tableName = self::OVERVIEWPAGE_TABLE;
        if (!$setup->tableExists($tableName)) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable($tableName))
                ->addColumn(
                    OverviewPageInterface::PAGE_ID,
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
                    OverviewPageInterface::ACTIVE,
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
                )->addColumn(
                    'updated_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                    'Update Time'
                )->addColumn(
                    OverviewPageInterface::URL_PATH,
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false,
                        'unique' => true
                    ],
                    'Url Path'
                )->addColumn(
                    OverviewPageInterface::HEADING,
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'H2 Heading'
                )->addColumn(
                    OverviewPageInterface::META_TITLE,
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Meta Title'
                )->addColumn(
                    OverviewPageInterface::META_KEYWORDS,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Meta Keywords'
                )->addColumn(
                    OverviewPageInterface::META_DESCRIPTION,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Meta Description'
                )->addColumn(
                    OverviewPageInterface::CONTENT_FIRST,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Content above results'
                )->addColumn(
                    OverviewPageInterface::CONTENT_LAST,
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Content below results'
                )->addColumn(
                    OverviewPageInterface::STORE_IDS,
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'Activated for stores'
                );
            $table->addIndex(
                $setup->getIdxName($tableName, [OverviewPageInterface::PAGE_ID]),
                [OverviewPageInterface::PAGE_ID]
            );
            $table->addIndex(
                $setup->getIdxName($tableName, [OverviewPageInterface::URL_PATH]),
                [OverviewPageInterface::URL_PATH]
            );

            $setup->getConnection()->createTable($table);
        }
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addOverviewPageFieldToPageTable(SchemaSetupInterface $setup)
    {
        $landingPageTable = $setup->getTable(self::LANDINGPAGE_TABLE);
        $overviewTable = $setup->getTable(self::OVERVIEWPAGE_TABLE);
        $connection = $setup->getConnection();

        $connection->addColumn(
            $landingPageTable,
            LandingPageInterface::OVERVIEW_PAGE_ID,
            [
                'nullable' => true,
                'unsigned' => true,
                'type' => Table::TYPE_INTEGER,
                'comment' => 'Link to overview page'
            ]
        );

        $connection->addForeignKey(
            $setup->getFkName(
                $landingPageTable,
                LandingPageInterface::OVERVIEW_PAGE_ID,
                $overviewTable,
                OverviewPageInterface::PAGE_ID
            ),
            $landingPageTable,
            LandingPageInterface::OVERVIEW_PAGE_ID,
            $overviewTable,
            OverviewPageInterface::PAGE_ID,
            Table::ACTION_SET_NULL
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addFilterLinkAllowedColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(self::LANDINGPAGE_TABLE),
            LandingPageInterface::FILTER_LINK_ALLOWED,
            [
                'default' => true,
                'type' => Table::TYPE_BOOLEAN,
                'comment' => 'Link to overview page'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addNameColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(self::LANDINGPAGE_TABLE),
            LandingPageInterface::NAME,
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Name of the landingpage'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable(self::OVERVIEWPAGE_TABLE),
            OverviewPageInterface::NAME,
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Name of the overview page'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addOverviewImageColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(self::LANDINGPAGE_TABLE),
            LandingPageInterface::OVERVIEW_PAGE_IMAGE,
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Optional image to show on overview page'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addCanonicalUrlColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(self::LANDINGPAGE_TABLE),
            LandingPageInterface::CANONICAL_URL,
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Canonical URL'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addHideSelectedFiltersColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(self::LANDINGPAGE_TABLE),
            LandingPageInterface::HIDE_SELECTED_FILTERS,
            [
                'default' => true,
                'type' => Table::TYPE_BOOLEAN,
                'comment' => 'Whether to hide selected filters'
            ]
        );
    }
}