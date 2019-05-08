<?php


namespace Emico\AttributeLanding\Controller\Adminhtml;

use Magento\Backend\App\Action;

abstract class Page extends Action
{
    const ADMIN_RESOURCE = 'Emico_AttributeLanding::top_level';

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Emico'), __('Emico'))
            ->addBreadcrumb(__('Page'), __('Page'));
        return $resultPage;
    }
}
