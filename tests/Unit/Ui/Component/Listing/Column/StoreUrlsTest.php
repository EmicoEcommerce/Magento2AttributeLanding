<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Ui\Component\Listing\Column;

use Emico\AttributeLanding\Ui\Component\Listing\Column\StoreUrls;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Mockery;
use Mockery\MockInterface;
use Tweakwise\Test\Support\UnitTester;

class StoreUrlsTest extends Unit
{
    protected UnitTester $tester;

    private ContextInterface|MockInterface $context;
    private UiComponentFactory|MockInterface $uiComponentFactory;
    private StoreRepositoryInterface|MockInterface $storeRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = Mockery::mock(ContextInterface::class);
        $this->uiComponentFactory = Mockery::mock(UiComponentFactory::class);
        $this->storeRepository = Mockery::mock(StoreRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testPrepareDataSourceReturnsInputWhenItemsAreMissing(): void
    {
        $subject = $this->createSubject('store_urls');
        $dataSource = ['data' => []];

        $this->assertSame($dataSource, $subject->prepareDataSource($dataSource));
    }

    public function testPrepareDataSourceFormatsStoreUrls(): void
    {
        $this->storeRepository->shouldReceive('getList')->andReturn([
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

        $this->assertSame(
            'Global: global-url<br/>Default Store: default/url<br/>Store 2: second/url',
            $result['data']['items'][0]['store_urls']
        );
        $this->assertSame('', $result['data']['items'][1]['store_urls']);
    }

    public function testPrepareDataSourceUsesConfiguredColumnName(): void
    {
        $this->storeRepository->shouldReceive('getList')->andReturn([
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

        $this->assertSame('Default Store: Landing Page Name', $result['data']['items'][0]['name']);
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

    private function createStore(int $id, string $name): StoreInterface|MockInterface
    {
        $store = Mockery::mock(StoreInterface::class);
        $store->shouldReceive('getId')->andReturn($id);
        $store->shouldReceive('getName')->andReturn($name);

        return $store;
    }
}
