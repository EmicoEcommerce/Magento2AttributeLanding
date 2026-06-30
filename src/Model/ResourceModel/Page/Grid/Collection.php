<?php

declare(strict_types=1);

namespace Emico\AttributeLanding\Model\ResourceModel\Page\Grid;

use Magento\Framework\DB\Select;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Zend_Db_Expr;

class Collection extends SearchResult
{
    /**
     * Add the store URL and store name aggregation just before the collection loads,
     * after SearchResult has finished setting up its base SELECT.
     *
     * @return $this
     */
    protected function _beforeLoad()
    {
        $this->getSelect()
            ->joinLeft(
                ['emico_attributelanding_page_store' => $this->getTable('emico_attributelanding_page_store')],
                'main_table.page_id = emico_attributelanding_page_store.page_id',
                [
                    'store_urls' => new Zend_Db_Expr(
                        'GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id, \':\', emico_attributelanding_page_store.url_path) '
                        . 'ORDER BY emico_attributelanding_page_store.store_id SEPARATOR \',\')'
                    ),
                    'name' => new Zend_Db_Expr(
                        'GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id, \':\', emico_attributelanding_page_store.name) '
                        . 'ORDER BY emico_attributelanding_page_store.store_id SEPARATOR \',\')'
                    ),
                ]
            )
            ->group('main_table.page_id');
        return parent::_beforeLoad();
    }

    /**
     * Override the count SELECT to include the store join and GROUP BY when a HAVING
     * clause is present, so GROUP_CONCAT aggregate filters work correctly during pagination.
     *
     * @return Select
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();

        $having = $countSelect->getPart(Select::HAVING);
        if (empty($having)) {
            return $countSelect;
        }

        $countSelect->joinLeft(
            ['emico_attributelanding_page_store' => $this->getTable('emico_attributelanding_page_store')],
            'main_table.page_id = emico_attributelanding_page_store.page_id',
            []
        );
        $countSelect->group('main_table.page_id');

        return $this->getConnection()->select()->from($countSelect, [new Zend_Db_Expr('COUNT(*)')]);
    }

    /**
     * Intercept store_urls and name filters and apply them as HAVING clauses so they
     * work against the GROUP_CONCAT aggregates instead of raw columns.
     *
     * @param string|array $field
     * @param mixed $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_urls') {
            $value = is_array($condition) ? ($condition['like'] ?? reset($condition)) : $condition;
            $value = trim((string) $value, '%');
            $this->getSelect()->having(
                'GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id, \':\', emico_attributelanding_page_store.url_path) ORDER BY emico_attributelanding_page_store.store_id SEPARATOR \',\') LIKE ?',
                '%' . $value . '%'
            );
            return $this;
        }

        if ($field === 'name') {
            $value = is_array($condition) ? ($condition['like'] ?? reset($condition)) : $condition;
            $value = trim((string) $value, '%');
            $this->getSelect()->having(
                'GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id, \':\', emico_attributelanding_page_store.name) ORDER BY emico_attributelanding_page_store.store_id SEPARATOR \',\') LIKE ?',
                '%' . $value . '%'
            );
            return $this;
        }

        return parent::addFieldToFilter($field, $condition);
    }
}
