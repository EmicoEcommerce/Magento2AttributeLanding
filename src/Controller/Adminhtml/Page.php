<?php


namespace Emico\AttributeLanding\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Page extends Action
{
    const ADMIN_MENU_RESOURCE = 'Emico_AttributeLanding::page_management';

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage
            ->setActiveMenu(self::ADMIN_MENU_RESOURCE)
            ->addBreadcrumb(__('Attribute landing pages'), __('Attribute landing pages'))
            ->addBreadcrumb(__('Manage pages'), __('Manage pages'));

        return $resultPage;
    }
}
