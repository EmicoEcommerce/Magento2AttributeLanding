<?php


namespace Emico\AttributeLanding\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;

abstract class OverviewPage extends Action
{
    const ADMIN_MENU_RESOURCE = 'Emico_AttributeLanding::overviewpage_management';

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    public function initPage($resultPage)
    {
        $resultPage
            ->setActiveMenu(self::ADMIN_MENU_RESOURCE)
            ->addBreadcrumb(__('Attribute landing pages'), __('Attribute landing pages'))
            ->addBreadcrumb(__('Manage overview pages'), __('Manage overview pages'));

        return $resultPage;
    }
}
