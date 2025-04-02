<?php

namespace Emico\AttributeLanding\Controller\Adminhtml\Page;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterfaceFactory;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var LandingPageInterfaceFactory
     */
    private $landingPageFactory;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param DataObjectHelper $dataObjectHelper
     * @param LandingPageRepositoryInterface $landingPageRepository
     * @param LandingPageInterfaceFactory $landingPageFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        DataObjectHelper $dataObjectHelper,
        LandingPageRepositoryInterface $landingPageRepository,
        LandingPageInterfaceFactory $landingPageFactory,
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
        $this->landingPageRepository = $landingPageRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->landingPageFactory = $landingPageFactory;
    }

    /**
     * Save action
     *
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $data[LandingPageInterface::STORE_ID] = (int)$data[LandingPageInterface::STORE_ID];

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $id = $this->getRequest()->getParam('page_id') ?? null;

        if (!$id) {
            $page = $this->landingPageFactory->create();
        } else {
            try {
                $page = $this->landingPageRepository->getByIdWithStore($id, $data[LandingPageInterface::STORE_ID]);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This Page no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        try {
            if ($id) {
                $data['id'] = $id;
            }

            $this->hydrateLandingPage($page, $data);
            $this->landingPageRepository->save($page);

            $this->messageManager->addSuccessMessage(__('You saved the Page.'));
            $this->dataPersistor->clear('emico_attributelanding_page');

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath(
                    '*/*/edit',
                    [
                        'page_id' => $page->getPageId(),
                        'store' => $page->getStoreId()
                    ]
                );
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Page.'));
        }

        $this->dataPersistor->set('emico_attributelanding_page', $data);
        return $resultRedirect->setPath('*/*/edit', ['page_id' => $page->getPageId(), 'store' => $page->getStoreId()]);
    }

    /**
     * @param LandingPageInterface $landingPage
     * @param array $data
     *
     * phpcs:disable Magento2.Security.InsecureFunction.FoundWithAlternative
     */
    protected function hydrateLandingPage(LandingPageInterface $landingPage, array $data)
    {
        if (!isset($data[LandingPageInterface::OVERVIEW_PAGE_IMAGE])) {
            $data[LandingPageInterface::OVERVIEW_PAGE_IMAGE] = null;
        } elseif (isset($data[LandingPageInterface::OVERVIEW_PAGE_IMAGE][0]['file'])) {
            $data[LandingPageInterface::OVERVIEW_PAGE_IMAGE] =
                $data[LandingPageInterface::OVERVIEW_PAGE_IMAGE][0]['file'];
        } else {
            unset($data[LandingPageInterface::OVERVIEW_PAGE_IMAGE]);
        }

        $filterAttributes = $data[LandingPageInterface::FILTER_ATTRIBUTES] ?? [];
        $filterAttributes = $this->sanitizeFilterAttributes($filterAttributes);
        $landingPage->setFilterAttributes(serialize($filterAttributes));

        if (empty($data[LandingPageInterface::OVERVIEW_PAGE_ID])) {
            $data[LandingPageInterface::OVERVIEW_PAGE_ID] = null;
        }

        $landingPage->setUrlPath($data[LandingPageInterface::URL_PATH]);
        $data[LandingPageInterface::URL_PATH] = $landingPage->getUrlPath();

        unset($data[LandingPageInterface::FILTER_ATTRIBUTES]);
        $this->dataObjectHelper->populateWithArray($landingPage, $data, LandingPageInterface::class);
    }

    /**
     * @param array $filterAttributes
     * @return array
     */
    protected function sanitizeFilterAttributes(array $filterAttributes): array
    {
        $allowedFields = ['attribute', 'value'];
        $sanitizedAttributes = [];
        foreach ($filterAttributes as $filterAttribute) {
            foreach (array_keys($filterAttribute) as $field) {
                if (!in_array($field, $allowedFields)) {
                    unset($filterAttribute[$field]);
                }
            }

            $sanitizedAttributes[] = $filterAttribute;
        }

        return $sanitizedAttributes;
    }
}
