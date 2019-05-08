<?php


namespace Emico\AttributeLanding\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;

abstract class OverviewPage extends Action
{
    const ADMIN_RESOURCE = 'Emico_AttributeLanding::top_level';

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
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Emico'), __('Emico'))
            ->addBreadcrumb(__('Page'), __('Page'));
        return $resultPage;
    }
}
