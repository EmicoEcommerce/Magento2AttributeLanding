<?php

declare(strict_types=1);

namespace Emico\AttributeLanding\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class StoreUrls extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly StoreRepositoryInterface $storeRepository,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source by replacing the raw store_urls string with a human-readable list.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $stores = $this->storeRepository->getList();
        $storeNames = [];
        foreach ($stores as $store) {
            $storeNames[(int) $store->getId()] = $store->getName();
        }

        $columnName = $this->getData('name');
        foreach (array_keys($dataSource['data']['items']) as $index) {
            $raw = $dataSource['data']['items'][$index][$columnName] ?? '';

            if ($raw === '') {
                $dataSource['data']['items'][$index][$columnName] = '';
                continue;
            }

            $lines = [];
            foreach (explode(',', (string) $raw) as $entry) {
                $parts = explode(':', $entry, 2);
                if (count($parts) !== 2) {
                    continue;
                }
                [$storeId, $urlPath] = $parts;
                $storeId = (int) $storeId;
                $storeName = $storeId === 0 ? __('Global') : ($storeNames[$storeId] ?? __('Store %1', $storeId));
                $lines[] = sprintf('%s: %s', $storeName, $urlPath);
            }

            $dataSource['data']['items'][$index][$columnName] = implode('<br/>', $lines);
        }

        return $dataSource;
    }
}
