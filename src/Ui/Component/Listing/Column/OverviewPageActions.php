<?php


namespace Emico\AttributeLanding\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreRepository;
use Magento\Ui\Component\Listing\Columns\Column;

class OverviewPageActions extends Column
{

    const URL_PATH_DETAILS = 'emico_attributelanding/overviewpage/details';
    protected $urlBuilder;
    const URL_PATH_EDIT = 'emico_attributelanding/overviewpage/edit';
    const URL_PATH_DELETE = 'emico_attributelanding/overviewpage/delete';

    public $storeRepository;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreRepository $storeRepository,
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
                                'title' => __('Delete "${ $.$data.title }"'),
                                'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
                            ]
                        ]
                    ];
                }

                if (!empty($item['store_ids'])) {
                    $store_ids = explode(',', $item['store_ids']);
                    $stores = $this->storeRepository->getList();
                    $item['stores'] = '';
                    foreach ($stores as $store) {
                        $id = $store->getId();
                        if (in_array($store->getId(), $store_ids)) {
                            $item['stores'] .= $store->getName() . ', ';
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
