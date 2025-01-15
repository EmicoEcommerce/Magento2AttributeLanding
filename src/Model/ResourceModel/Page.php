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
    protected function _construct()
    {
        $this->_init('emico_attributelanding_page', LandingPageInterface::PAGE_ID);
    }


    public function getLandingPageStoreData($landingPageId, $storeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('emico_attributelanding_page_store'))
            ->where('page_id = ?', $landingPageId)
            ->where('store_id = ?', $storeId);
        return $connection->fetchRow($select);
    }
}
