<?php

declare(strict_types=1);

namespace Emico\AttributeLanding\Model\OverviewPage;

use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Emico\AttributeLanding\Model\OverviewPage;
use Emico\AttributeLanding\Model\OverviewPageRepository;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage\Collection;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected array $loadedData;
    /**
     * @var Collection
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
     * @param Http   $request
     * @param OverviewPageRepository $overviewPageRepository
     * @param array  $meta
     * @param array  $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        protected DataPersistorInterface $dataPersistor,
        private readonly Http $request,
        private readonly OverviewPageRepository $overviewPageRepository,
        array $meta = [],
        array $data = [],
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $storeId = (int) $this->request->getParam('store', 0);
        /** @var UrlRewriteGeneratorInterface $model */
        foreach ($this->collection->getItems() as $model) {
            $storeData = $this->overviewPageRepository->getByIdWithStore($model->getPageId(), $storeId)->getData();

            /** @var OverviewPage $model */
            $this->loadedData[$model->getPageId()] = array_map(fn($value) => $value, $storeData);
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
