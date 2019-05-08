<?php
/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2019.
 */

namespace Emico\AttributeLanding\Model\ResourceModel\Page;

use Emico\AttributeLanding\Model\LandingPage;
use Emico\AttributeLanding\Model\ResourceModel\Page as PageResourceModel;
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
        $this->_init(LandingPage::class, PageResourceModel::class);
    }

}