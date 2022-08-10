<?php


namespace Emico\AttributeLanding\Ui\Component\Listing\Column;

use Magento\Catalog\Model\CategoryRepository;
use Magento\Store\Model\StoreRepository;

class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Catalog\Model\CategoryRepository
     */
    public CategoryRepository $categoryRepostory;

    /**
     * @var \Magento\Store\Model\StoreRepository
     */
    public StoreRepository $storeRepository;

    const URL_PATH_DETAILS = 'emico_attributelanding/page/details';
    const URL_PATH_EDIT = 'emico_attributelanding/page/edit';
    const URL_PATH_DELETE = 'emico_attributelanding/page/delete';
    const URL_PATH_DUPLICATE = 'emico_attributelanding/page/duplicate';

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Store\Model\StoreRepository $storeRepository,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->storeRepository = $storeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->urlBuilder = $urlBuilder;

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
                        'duplicate' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DUPLICATE,
                                [
                                    'page_id' => $item['page_id']
                                ]
                            ),
                            'label' => __('Duplicate')
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

                if (isset($item['category_id'])) {
                    $category = $this->categoryRepository->get($item['category_id']);
                    $item['category'] = $category->getName();
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
