<?php

namespace Emico\AttributeLanding\Controller\Adminhtml\Page;

use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Controller\Adminhtml\Page;
use Magento\Backend\App\Action\Context;

class Delete extends Page
{
    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param LandingPageRepositoryInterface $landingPageRepository
     */
    public function __construct(
        Context $context,
        LandingPageRepositoryInterface $landingPageRepository
    ) {
        $this->landingPageRepository = $landingPageRepository;

        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('page_id');

        if ($id) {
            try {
                $this->landingPageRepository->deleteById($id);

                $this->messageManager->addSuccessMessage(__('You deleted the Page.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['page_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a Page to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
