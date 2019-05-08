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
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('page_id');

        if ($id) {
            try {
                $landingPage = $this->landingPageRepository->getById($id);
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
            $id ? __('Edit Page') : __('New Page'),
            $id ? __('Edit Page') : __('New Page')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Pages'));
        $resultPage->getConfig()->getTitle()->prepend($id ? __('Edit Page %1', $landingPage->getPageId()) : __('New Page'));

        return $resultPage;
    }
}
