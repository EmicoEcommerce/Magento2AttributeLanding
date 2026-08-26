<?php

declare(strict_types=1);

namespace Emico\AttributeLandingTest\Unit\Ui\Component\Listing\Column;

use Emico\AttributeLanding\Ui\Component\Listing\Column\StoreUrls;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Mockery;
use Mockery\MockInterface;
use Emico\AttributeLandingTest\Support\UnitTester;
use Throwable;

class StoreUrlsTest extends Unit
{
    protected UnitTester $tester;
    private ContextInterface|MockInterface $context;
    private UiComponentFactory|MockInterface $uiComponentFactory;
    private StoreRepositoryInterface|MockInterface $storeRepository;

    public function testPrepareDataSourceReturnsInputWhenItemsAreMissing(): void
    {
        $subject = $this->createSubject('store_urls');
        $dataSource = ['data' => []];

        $this->tester->assertSame($dataSource, $subject->prepareDataSource($dataSource));
    }

    /**
     * @throws Throwable
     */
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

        $this->tester->assertSame(
            'Global: global-url<br/>Default Store: default/url<br/>Store 2: second/url',
            $result['data']['items'][0]['store_urls'],
        );
        $this->tester->assertSame('', $result['data']['items'][1]['store_urls']);
    }

    /**
     * @throws Throwable
     */
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

        $this->tester->assertSame('Default Store: Landing Page Name', $result['data']['items'][0]['name']);
    }

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->context = Mockery::mock(ContextInterface::class);
        $this->uiComponentFactory = Mockery::mock(UiComponentFactory::class);
        $this->storeRepository = Mockery::mock(StoreRepositoryInterface::class);
    }

    /**
     * @throws Throwable
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    private function createSubject(string $columnName): StoreUrls
    {
        return new StoreUrls(
            $this->context,
            $this->uiComponentFactory,
            $this->storeRepository,
            [],
            ['name' => $columnName],
        );
    }

    /**
     * @throws Throwable
     */
    private function createStore(int $id, string $name): StoreInterface|MockInterface
    {
        $store = Mockery::mock(StoreInterface::class);
        $store->shouldReceive('getId')->andReturn($id);
        $store->shouldReceive('getName')->andReturn($name);

        return $store;
    }
}
