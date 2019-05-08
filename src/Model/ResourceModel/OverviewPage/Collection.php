<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2019.
 */

namespace Emico\AttributeLanding\Model\ResourceModel\OverviewPage;

use Emico\AttributeLanding\Model\OverviewPage;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage as PageResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'page_id';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(OverviewPage::class, PageResourceModel::class);
    }

}