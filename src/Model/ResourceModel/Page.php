<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\ResourceModel;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Event\ManagerInterface as EventManager;

class Page extends AbstractDb
{
    public function __construct(
        private Context $context,
        private EventManager $eventManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->eventManager = $eventManager;
    }
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
     * Get all store data for a given landing page ID.
     *
     * @param int $pageId
     * @return array
     */
    public function getAllLandingPageStoreData(int $pageId): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('emico_attributelanding_page_store'))
            ->where('page_id = :page_id');

        $bind = ['page_id' => (int)$pageId];

        return $connection->fetchAll($select, $bind);
    }

    /**
     * @param       $landingPageId
     * @param       $storeId
     * @param array $data
     *
     * @return void
     */
    public function saveLandingPageStoreData(LandingPageInterface $page): void
    {
        $data = $page->getData();
        $connection = $this->getConnection();
        $table = $this->getTable('emico_attributelanding_page_store');
        $where = [
            'page_id = ?' => $page->getPageId(),
            'store_id = ?' => $page->getStoreId()
        ];

        unset($data[LandingPageInterface::OVERVIEW_PAGE_ID]);
        unset($data[LandingPageInterface::OVERVIEW_PAGE_IMAGE]);
        unset($data[LandingPageInterface::CREATED_AT]);
        unset($data[LandingPageInterface::UPDATED_AT]);
        unset($data['id']);

        if (!empty($this->getLandingPageStoreData($page->getPageId(), $page->getStoreId()))) {
            $connection->update($table, $data, $where);
        } else {
            unset($data['id']);
            $data['page_id'] = $page->getPageId();
            $data['store_id'] = $page->getStoreId();
            $connection->insert($table, $data);
        }

        $this->eventManager->dispatch('emico_attributelanding_page_save_after', ['object' => $page]);
    }
}
