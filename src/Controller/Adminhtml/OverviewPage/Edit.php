<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Controller\Adminhtml\OverviewPage;

use Emico\AttributeLanding\Api\Data\OverviewPageInterfaceFactory;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Emico\AttributeLanding\Controller\Adminhtml\OverviewPage;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Edit extends OverviewPage
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var OverviewPageRepositoryInterface
     */
    private $overviewPageRepository;

    /**
     * @var OverviewPageInterfaceFactory
     */
    private $overviewPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param OverviewPageRepositoryInterface $overviewPageRepository
     * @param OverviewPageInterfaceFactory $overviewPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        OverviewPageRepositoryInterface $overviewPageRepository,
        OverviewPageInterfaceFactory $overviewPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->overviewPageRepository = $overviewPageRepository;
        $this->overviewPageFactory = $overviewPageFactory;
    }

    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\Controller\ResultInterface
     * @throws InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('page_id');

        if ($id) {
            try {
                $landingPage = $this->overviewPageRepository->getById($id);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This Page no longer exists.'));

                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $landingPage = $this->overviewPageFactory->create();
        }

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit overview page') : __('New overview page'),
            $id ? __('Edit overview page') : __('New overview page')
        );

        $resultPage->getConfig()->getTitle()->prepend(
            $id ? __('Edit overview page %1', $landingPage->getPageId()) : __('New overview page')
        );

        return $resultPage;
    }
}
