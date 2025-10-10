<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Controller\Adminhtml\OverviewPage;

use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Emico\AttributeLanding\Controller\Adminhtml\OverviewPage;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

class Delete extends OverviewPage
{
    /**
     * @var OverviewPageRepositoryInterface
     */
    private $overviewPageRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param OverviewPageRepositoryInterface $overviewPageRepository
     */
    public function __construct(
        Context $context,
        OverviewPageRepositoryInterface $overviewPageRepository
    ) {
        $this->overviewPageRepository = $overviewPageRepository;

        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('page_id');

        if ($id) {
            try {
                $this->overviewPageRepository->deleteById($id);

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
