<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\ResourceModel;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Page extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('emico_attributelanding_page', LandingPageInterface::PAGE_ID);
    }


    /**
     * @param $landingPageId
     * @param $storeId
     *
     * @return array
     */
    public function getLandingPageStoreData($landingPageId, $storeId = 0): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('emico_attributelanding_page_store'))
            ->where('page_id = ?', $landingPageId)
            ->where('store_id = ?', $storeId);
       if ($result = $connection->fetchRow($select)) {
            return $result;
       }

       return [];
    }

    /**
     * @param       $landingPageId
     * @param       $storeId
     * @param array $data
     *
     * @return void
     */
    public function saveLandingPageStoreData($landingPageId, $storeId, array $data): void
    {
        $connection = $this->getConnection();
        $table = $this->getTable('emico_attributelanding_page_store');
        $where = [
            'page_id = ?' => $landingPageId,
            'store_id = ?' => $storeId
        ];

        unset($data[LandingPageInterface::OVERVIEW_PAGE_ID]);
        unset($data[LandingPageInterface::OVERVIEW_PAGE_IMAGE]);

        if (!empty($this->getLandingPageStoreData($landingPageId, $storeId))) {
            $connection->update($table, $data, $where);
        } else {
            $data['page_id'] = $landingPageId;
            $data['store_id'] = $storeId;
            $connection->insert($table, $data);
        }
    }
}
