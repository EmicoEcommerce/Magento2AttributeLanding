<?php

/**
 * @author : Edwin Jacobs, email: ejacobs@emico.nl.
 * @copyright : Copyright Emico B.V. 2019.
 */

namespace Emico\AttributeLanding\Controller\OverviewPage;

use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

class View extends Action
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
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LandingPageContext $landingPageContext
     * @param OverviewPageRepositoryInterface $overviewPageRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LandingPageContext $landingPageContext,
        OverviewPageRepositoryInterface $overviewPageRepository,
        private readonly StoreManagerInterface $storeManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->overviewPageRepository = $overviewPageRepository;
        $this->landingPageContext = $landingPageContext;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): ResultInterface
    {
        $pageId = (int)$this->getRequest()->getParam('id');
        $storeId = (int)$this->storeManager->getStore()->getId();
        $overviewPage = $this->overviewPageRepository->getByIdWithStore($pageId, $storeId);

        if (!$overviewPage->isActive()) {
            throw new NotFoundException(__('Page not active'));
        }

        $this->landingPageContext->setOverviewPage($overviewPage);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getLayout()
            ->getUpdate()
            ->addHandle('emico_attributelanding_overviewpage_view_' . $overviewPage->getName());
        return $resultPage;
    }
}
