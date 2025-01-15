<?php

namespace Emico\AttributeLanding\Controller\Adminhtml\Page;

use Emico\AttributeLanding\Api\Data\LandingPageInterfaceFactory;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Controller\Adminhtml\Page;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Page
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * @var LandingPageInterfaceFactory
     */
    private $landingPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LandingPageRepositoryInterface $landingPageRepository
     * @param LandingPageInterfaceFactory $landingPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LandingPageRepositoryInterface $landingPageRepository,
        LandingPageInterfaceFactory $landingPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->landingPageRepository = $landingPageRepository;
        $this->landingPageFactory = $landingPageFactory;
    }

    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('page_id');
        $storeId = $this->getRequest()->getParam('store', 0);

        if ($id) {
            try {
                $landingPage = $this->landingPageRepository->getByIdWithStore($id, $storeId);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This Page no longer exists.'));

                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $landingPage = $this->landingPageFactory->create();
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit page') : __('New page'),
            $id ? __('Edit page') : __('New page')
        );

        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit page %1', $landingPage->getPageId()) : __('New page')
        );

        return $resultPage;
    }
}
