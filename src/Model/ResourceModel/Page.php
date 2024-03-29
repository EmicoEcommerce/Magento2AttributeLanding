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
}
