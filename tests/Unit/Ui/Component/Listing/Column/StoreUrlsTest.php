<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Ui\Component\Listing\Column;

use Emico\AttributeLanding\Ui\Component\Listing\Column\StoreUrls;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;

class StoreUrlsTest extends Unit
{
    private ContextInterface|MockObject $context;
    private UiComponentFactory|MockObject $uiComponentFactory;
    private StoreRepositoryInterface|MockObject $storeRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = $this->createMock(ContextInterface::class);
        $this->uiComponentFactory = $this->createMock(UiComponentFactory::class);
        $this->storeRepository = $this->createMock(StoreRepositoryInterface::class);
    }

    public function testPrepareDataSourceReturnsInputWhenItemsAreMissing(): void
    {
        $subject = $this->createSubject('store_urls');
        $dataSource = ['data' => []];

        self::assertSame($dataSource, $subject->prepareDataSource($dataSource));
    }

    public function testPrepareDataSourceFormatsStoreUrls(): void
    {
        $this->storeRepository->method('getList')->willReturn([
            $this->createStore(1, 'Default Store'),
        ]);

        $subject = $this->createSubject('store_urls');
        $result = $subject->prepareDataSource([
            'data' => [
                'items' => [
                    ['store_urls' => '0:global-url,1:default/url,2:second/url,broken-entry'],
                    ['store_urls' => null],
                ],
            ],
        ]);

        self::assertSame(
            'Global: global-url<br/>Default Store: default/url<br/>Store 2: second/url',
            $result['data']['items'][0]['store_urls']
        );
        self::assertSame('', $result['data']['items'][1]['store_urls']);
    }

    public function testPrepareDataSourceUsesConfiguredColumnName(): void
    {
        $this->storeRepository->method('getList')->willReturn([
            $this->createStore(1, 'Default Store'),
        ]);

        $subject = $this->createSubject('name');
        $result = $subject->prepareDataSource([
            'data' => [
                'items' => [
                    ['name' => '1:Landing Page Name'],
                ],
            ],
        ]);

        self::assertSame('Default Store: Landing Page Name', $result['data']['items'][0]['name']);
    }

    private function createSubject(string $columnName): StoreUrls
    {
        return new StoreUrls(
            $this->context,
            $this->uiComponentFactory,
            $this->storeRepository,
            [],
            ['name' => $columnName]
        );
    }

    private function createStore(int $id, string $name): StoreInterface
    {
        $store = $this->createMock(StoreInterface::class);
        $store->method('getId')->willReturn($id);
        $store->method('getName')->willReturn($name);

        return $store;
    }
}
