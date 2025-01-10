<?php

namespace Emico\AttributeLanding\Model\ResourceModel;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PageStore extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('emico_attributelanding_page_store', 'id');
    }
}
