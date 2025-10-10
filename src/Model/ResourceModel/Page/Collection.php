<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

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
    protected $_idFieldName = 'id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(LandingPage::class, PageResourceModel::class);
    }

    /**
     * Initialize select with join
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->join(
            ['emico_attributelanding_page_store' => $this->getTable('emico_attributelanding_page_store')],
            'main_table.page_id = emico_attributelanding_page_store.page_id',
            ['*']
        );
        return $this;
    }
}
