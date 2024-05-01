<?php

namespace Emico\AttributeLanding\Controller\Adminhtml\Page;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterfaceFactory;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Controller\Adminhtml\Page;
use Emico\AttributeLanding\Model\LandingPage;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Duplicate extends Page
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
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('page_id');

        try {
            /** @var LandingPage $existingLandingPage */
            $existingLandingPage = $this->landingPageRepository->getById($id);

            /** @var LandingPage $newLandingPage */
            $newLandingPage = $this->landingPageFactory->create();
            $existingData = $existingLandingPage->getData();
            unset($existingData[LandingPageInterface::PAGE_ID]);
            $newLandingPage->setData($existingData);
            $newLandingPage->setUrlPath($existingLandingPage->getUrlPath() . '-copy');
            $newLandingPage = $this->landingPageRepository->save($newLandingPage);

            $this->messageManager->addNoticeMessage('Your page has been duplicated');
            $this->messageManager->addWarningMessage('Do not forget to change the URL path');
            return $resultRedirect->setPath('*/*/edit', ['page_id' => $newLandingPage->getId()]);
        } catch (NoSuchEntityException $exception) {
            $this->messageManager->addErrorMessage(__('This Page no longer exists.'));

            return $resultRedirect->setPath('*/*/');
        }
    }
}
