<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Setup\Patch\Data;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class ConvertLandingpageEntries implements DataPatchInterface
{
    /**
     * ConvertLandingpageEntries constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): ConvertLandingpageEntries
    {
        $this->moduleDataSetup->startSetup();

        $connection = $this->moduleDataSetup->getConnection();
        $landingPageTable = $this->moduleDataSetup->getTable('emico_attributelanding_page');
        $landingPageStoreTable = $this->moduleDataSetup->getTable('emico_attributelanding_page_store');

        $select = $connection->select()->from($landingPageTable);
        $landingPages = $connection->fetchAll($select);

        foreach ($landingPages as $landingPage) {
            $storeIds = explode(',', $landingPage['store_ids']);
            foreach ($storeIds as $storeId) {
                /** @phpstan-ignore-next-line */
                $this->insertLandingPageStore($connection, $landingPageStoreTable, $landingPage, $storeId);
            }
        }

        $this->moduleDataSetup->endSetup();

        return $this;
    }

    /**
     * @param AdapterInterface $connection
     * @param string $table
     * @param array $landingPage
     * @param int $storeId
     */
    private function insertLandingPageStore(
        AdapterInterface $connection,
        string $table,
        array $landingPage,
        int $storeId
    ): void {
        $data = [
            'page_id' => $landingPage['page_id'],
            'store_id' => $storeId,
        ];

        $fields = [
            LandingPageInterface::ACTIVE,
            LandingPageInterface::URL_PATH,
            LandingPageInterface::CATEGORY_ID,
            LandingPageInterface::HEADING,
            LandingPageInterface::HEADER_IMAGE,
            LandingPageInterface::META_TITLE,
            LandingPageInterface::META_KEYWORDS,
            LandingPageInterface::META_DESCRIPTION,
            LandingPageInterface::CONTENT_FIRST,
            LandingPageInterface::CONTENT_LAST,
            LandingPageInterface::FILTER_ATTRIBUTES,
            LandingPageInterface::TWEAKWISE_FILTER_TEMPLATE,
            LandingPageInterface::FILTER_LINK_ALLOWED,
            LandingPageInterface::CANONICAL_URL,
            LandingPageInterface::HIDE_SELECTED_FILTERS,
            LandingPageInterface::TWEAKWISE_SORT_TEMPLATE,
            LandingPageInterface::TWEAKWISE_BUILDER_TEMPLATE
        ];

        foreach ($fields as $field) {
            if (!isset($landingPage[$field])) {
                continue;
            }

            $data[$field] = $landingPage[$field];
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
