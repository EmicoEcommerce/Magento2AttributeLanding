<?php

declare(strict_types=1);

namespace Emico\AttributeLanding\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class FixLandingPageStoreName implements DataPatchInterface
{
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
    ) {
    }

    /**
     * Back-fill the name column in emico_attributelanding_page_store for rows
     * that were inserted by ConvertLandingpageEntries without the name field.
     *
     * @return $this
     */
    public function apply(): FixLandingPageStoreName
    {
        $this->moduleDataSetup->startSetup();

        $connection = $this->moduleDataSetup->getConnection();
        $storeTable = $this->moduleDataSetup->getTable('emico_attributelanding_page_store');
        $pageTable = $this->moduleDataSetup->getTable('emico_attributelanding_page');

        $connection->query(
            sprintf(
                'UPDATE %s s JOIN %s p ON s.page_id = p.page_id SET s.name = p.name WHERE s.name IS NULL',
                $storeTable,
                $pageTable
            )
        );

        $this->moduleDataSetup->endSetup();

        return $this;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [ConvertLandingpageEntries::class];
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
