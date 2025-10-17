<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Model\OverviewPage;

use Emico\AttributeLanding\Model\OverviewPage;
use Emico\AttributeLanding\Model\ResourceModel\OverviewPage\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

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
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
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
        /** @phpstan-ignore-next-line */
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        /** @phpstan-ignore-next-line */
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            /** @var OverviewPage $model */
            $this->loadedData[$model->getPageId()] = $model->getData();
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
