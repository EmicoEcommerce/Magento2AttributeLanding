<?php

namespace Emico\AttributeLanding\Controller\Adminhtml\OverviewPage;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\Data\OverviewPageInterfaceFactory;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var OverviewPageRepositoryInterface
     */
    private $overviewPageRepository;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var OverviewPageInterfaceFactory
     */
    private $overviewPageFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param DataObjectHelper $dataObjectHelper
     * @param OverviewPageRepositoryInterface $overviewPageRepository
     * @param OverviewPageInterfaceFactory $overviewPageFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        DataObjectHelper $dataObjectHelper,
        OverviewPageRepositoryInterface $overviewPageRepository,
        OverviewPageInterfaceFactory $overviewPageFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->overviewPageRepository = $overviewPageRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->overviewPageFactory = $overviewPageFactory;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $id = $this->getRequest()->getParam('page_id');
        if (!$id) {
            $page = $this->overviewPageFactory->create();
        } else {
            try {
                $page = $this->overviewPageRepository->getById($id);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This Page no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->dataObjectHelper->populateWithArray($page, $data, OverviewPageInterface::class);

        try {
            $this->overviewPageRepository->save($page);

            $this->messageManager->addSuccessMessage(__('You saved the Page.'));
            $this->dataPersistor->clear('emico_attributelanding_overviewpage');

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['page_id' => $page->getPageId()]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Page.'));
        }

        $this->dataPersistor->set('emico_attributelanding_overviewpage', $data);
        return $resultRedirect->setPath('*/*/edit', ['page_id' => $this->getRequest()->getParam('page_id')]);
    }
}
