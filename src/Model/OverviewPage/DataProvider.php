<?php

namespace Emico\AttributeLanding\Model\OverviewPage;

use Emico\AttributeLanding\Model\OverviewPage;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Emico\AttributeLanding\Model\OverviewPageRepository;
use Magento\Framework\App\Request\Http;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Emico\AttributeLanding\Model\ResourceModel\OverviewPage\Collection
     */
    protected $collection;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param Http $request
     * @param OverviewPageRepository $overviewPageRepository
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        private readonly Http $request,
        private readonly overviewPageRepository $overviewPageRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $storeId = (int)$this->request->getParam('store', 0);
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $storeData = $this->overviewPageRepository->getByIdWithStore($model->getPageId(), $storeId)->getData();

            foreach ($storeData as $key => $value) {
                $modelData[$key] = $value;
            }

            /** @var OverviewPage $model */
            $this->loadedData[$model->getPageId()] = $modelData;
        }

        $data = $this->dataPersistor->get('emico_attributelanding_overviewpage');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getPageId()] = $model->getData();
            $this->dataPersistor->clear('emico_attributelanding_overviewpage');
        }

        return $this->loadedData;
    }
}
