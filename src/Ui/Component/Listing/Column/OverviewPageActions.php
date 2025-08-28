<?php

namespace Emico\AttributeLanding\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class OverviewPageActions extends Column
{
    public const URL_PATH_DETAILS = 'emico_attributelanding/overviewpage/details';

    protected $urlBuilder;
    protected const URL_PATH_EDIT = 'emico_attributelanding/overviewpage/edit';
    protected const URL_PATH_DELETE = 'emico_attributelanding/overviewpage/delete';

    public StoreRepositoryInterface $storeRepository;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreRepositoryInterface $storeRepository,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->storeRepository = $storeRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     *
     * phpcs:disable Generic.Metrics.NestingLevel.TooHigh
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['page_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'page_id' => $item['page_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'page_id' => $item['page_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete overviewpage'),
                                'message' => __('Are you sure you wan\'t to delete this overviewpage?')
                            ]
                        ]
                    ];
                }

                if (!empty($item['store_ids']) && is_string($item['store_ids'])) {
                    $store_ids = explode(',', $item['store_ids']);
                    if (!empty($store_ids) && is_array($store_ids)) {
                        $stores = $this->storeRepository->getList();
                        $item['stores'] = '';
                        foreach ($stores as $store) {
                            $id = $store->getId();
                            if (in_array($id, $store_ids)) {
                                if ($id === "0") {
                                    $item['stores'] .= 'All Store Views' . ', ';
                                } else {
                                    $item['stores'] .= $store->getName() . ', ';
                                }
                            }
                        }
                    }
                } else {
                    $item['stores'] = 'All Store Views';
                }
            }
        }

        return $dataSource;
    }
}
