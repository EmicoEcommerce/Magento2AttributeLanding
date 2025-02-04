<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\Page;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Model\LandingPage;
use Emico\AttributeLanding\Model\LandingPageRepository;
use Emico\AttributeLanding\Model\ResourceModel\Page\Collection;
use Emico\AttributeLanding\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Request\Http;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
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
     * @var Collection
     */
    protected $collection;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ImageUploader $imageUploader
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        ImageUploader $imageUploader,
        private Http $request,
        private LandingPageRepository $landingPageRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->imageUploader = $imageUploader;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if ($this->loadedData !== null) {
            return $this->loadedData;
        }

        $storeId = $this->request->getParam('store', 0);

        $items = $this->collection->getItems();
        foreach ($items as $model) {

            $modelData = $model->getData();
            //merge $modelData with landingpageRepository getbyIdWithStore
            $modelData = array_merge($modelData, $this->landingPageRepository->getByIdWithStore($model->getPageId(), $storeId)->getData());
            if ($model->getOverviewPageImage()) {
                $modelData[LandingPageInterface::OVERVIEW_PAGE_IMAGE] = [
                    [
                        'name' => $model->getOverviewPageImage(),
                        'url' => $this->imageUploader->getMediaUrl($model->getOverviewPageImage())
                    ]
                ];
            }

            $modelData[LandingPageInterface::FILTER_ATTRIBUTES] = $model->getUnserializedFilterAttributes();
            $modelData[LandingPageInterface::STORE_ID] = $storeId;

            $this->loadedData[$model->getPageId()] = $modelData;
        }

        $data = $this->dataPersistor->get('emico_attributelanding_page');
        $data['store_id'] = $storeId;

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getPageId()] = $model->getData();
            $this->dataPersistor->clear('emico_attributelanding_page');
        }

        return $this->loadedData;
    }
}
