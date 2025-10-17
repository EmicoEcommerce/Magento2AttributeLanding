<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\ResourceModel;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OverviewPage extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('emico_attributelanding_overviewpage', LandingPageInterface::PAGE_ID);
    }

    public function getOverviewPageStoreData(int $overviewPageId, int $storeId = 0): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from(['ps' => $this->getTable('emico_attributelanding_overviewpage_store')])
            ->where('ps.page_id = ?', $overviewPageId)
            ->where('store_id = ?', $storeId);

        $result = $connection->fetchRow($select);

        if ($result) {
            return $result;
        }

        return [];
    }

    public function getAllOverviewPageStoreData(int $overviewPageId): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('emico_attributelanding_overviewpage_store'))
            ->where('page_id = :page_id');

        $bind = ['page_id' => (int)$overviewPageId];

        return $connection->fetchAll($select, $bind);
    }

    public function saveOverviewPageStoreData(OverviewPageInterface $page): void
    {
        $data = $page->getOverviewPageDataForStore();
        $connection = $this->getConnection();
        $table = $this->getTable('emico_attributelanding_overviewpage_store');
        $where = [
            'page_id = ?' => $page->getPageId(),
            'store_id = ?' => $page->getStoreId()
        ];

        unset($data['id']);

        if (!empty($this->getOverviewPageStoreData($page->getPageId(), $page->getStoreId()))) {
            $connection->update($table, $data, $where);
        } else {
            $data['page_id'] = $page->getPageId();
            $data['store_id'] = $page->getStoreId();
            $connection->insert($table, $data);
        }
    }
}
