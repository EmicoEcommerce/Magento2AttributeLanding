<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model\ResourceModel\Page\Grid;

use Emico\AttributeLanding\Model\ResourceModel\Page\Grid\Collection as GridCollection;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use Magento\Framework\DB\Select\SelectRenderer;
use Magento\Framework\Event\ManagerInterface;
use Mockery;
use Mockery\MockInterface;
use Tweakwise\Test\Support\UnitTester;
use Zend_Db_Expr;

class CollectionTest extends Unit
{
    protected UnitTester $tester;

    private Mysql|MockInterface $connection;
    private SelectRenderer|MockInterface $selectRenderer;
    private ManagerInterface|MockInterface $eventManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->selectRenderer = Mockery::mock(SelectRenderer::class);
        $this->eventManager = Mockery::mock(ManagerInterface::class);
        $this->eventManager->shouldReceive('dispatch')->andReturnNull();

        $this->connection = Mockery::mock(Mysql::class)->makePartial();
        $this->connection->shouldReceive('quoteIdentifier')->andReturnUsing(
            static fn (string $identifier): string => $identifier
        );
        $this->connection->shouldReceive('quoteInto')->andReturnUsing(
            static fn (string $text, mixed $value): string => str_replace('?', sprintf("'%s'", (string) $value), $text)
        );
        $this->connection->shouldReceive('select')->andReturnUsing(fn (): Select => $this->createSelect());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testBeforeLoadAddsStoreJoinAndAggregatedColumns(): void
    {
        $select = $this->createSelect();
        $select->from(['main_table' => 'emico_attributelanding_page'], ['page_id']);

        $subject = $this->createSubject($select);
        $subject->beforeLoad();

        $from = $select->getPart(Select::FROM);
        $columns = $select->getPart(Select::COLUMNS);

        $this->assertArrayHasKey('emico_attributelanding_page_store', $from);
        $this->assertSame(['main_table.page_id'], $select->getPart(Select::GROUP));
        $this->assertCount(3, $columns);
        $this->assertSame('store_urls', $columns[1][2]);
        $this->assertInstanceOf(Zend_Db_Expr::class, $columns[1][1]);
        $this->assertSame('name', $columns[2][2]);
        $this->assertInstanceOf(Zend_Db_Expr::class, $columns[2][1]);
    }

    public function testAddFieldToFilterAddsHavingForStoreUrls(): void
    {
        $select = $this->createSelect();
        $subject = $this->createSubject($select);

        $subject->addFieldToFilter('store_urls', ['like' => '%default/url%']);

        $having = implode(' ', $select->getPart(Select::HAVING));

        $this->assertStringContainsString('GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id', $having);
        $this->assertStringContainsString("'%default/url%'", $having);
    }

    public function testAddFieldToFilterAddsHavingForName(): void
    {
        $select = $this->createSelect();
        $subject = $this->createSubject($select);

        $subject->addFieldToFilter('name', ['like' => '%Landing Page%']);

        $having = implode(' ', $select->getPart(Select::HAVING));

        $this->assertStringContainsString('GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id', $having);
        $this->assertStringContainsString('emico_attributelanding_page_store.name', $having);
        $this->assertStringContainsString("'%Landing Page%'", $having);
    }

    public function testGetSelectCountSqlWrapsGroupedQueryWhenHavingIsPresent(): void
    {
        $select = $this->createSelect();
        $select->from(['main_table' => 'emico_attributelanding_page'], ['page_id']);
        $select->having('COUNT(*) > ?', 0);

        $subject = $this->createSubject($select);
        $countSelect = $subject->getSelectCountSql();

        $from = $countSelect->getPart(Select::FROM);
        $innerSelect = reset($from)['tableName'];

        $this->assertInstanceOf(Select::class, $innerSelect);
        $this->assertArrayHasKey('emico_attributelanding_page_store', $innerSelect->getPart(Select::FROM));
        $this->assertSame(['main_table.page_id'], $innerSelect->getPart(Select::GROUP));
    }

    private function createSubject(Select $select): GridCollection
    {
        return new class ($select, $this->connection, $this->eventManager) extends GridCollection {
            public function __construct(
                Select $select,
                Mysql $connection,
                ManagerInterface $eventManager
            ) {
                $this->_select = $select;
                $this->_conn = $connection;
                $this->_eventManager = $eventManager;
            }

            public function beforeLoad(): self
            {
                return $this->_beforeLoad();
            }

            public function getConnection()
            {
                return $this->_conn;
            }

            public function getSelect()
            {
                return $this->_select;
            }

            public function getTable($table)
            {
                return $table;
            }
        };
    }

    private function createSelect(): Select
    {
        return new Select($this->connection, $this->selectRenderer);
    }
}
