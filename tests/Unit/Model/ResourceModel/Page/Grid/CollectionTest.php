<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model\ResourceModel\Page\Grid;

use Emico\AttributeLanding\Model\ResourceModel\Page\Grid\Collection as GridCollection;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use Magento\Framework\DB\Select\SelectRenderer;
use Magento\Framework\Event\ManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Zend_Db_Expr;

class CollectionTest extends Unit
{
    private Mysql|MockObject $connection;
    private SelectRenderer|MockObject $selectRenderer;
    private ManagerInterface|MockObject $eventManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->selectRenderer = $this->createMock(SelectRenderer::class);
        $this->eventManager = $this->createMock(ManagerInterface::class);

        $this->connection = $this->getMockBuilder(Mysql::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['quoteIdentifier', 'quoteInto', 'select'])
            ->getMock();
        $this->connection->method('quoteIdentifier')->willReturnCallback(
            static fn (string $identifier): string => $identifier
        );
        $this->connection->method('quoteInto')->willReturnCallback(
            static fn (string $text, mixed $value): string => str_replace('?', sprintf("'%s'", (string) $value), $text)
        );
        $this->connection->method('select')->willReturnCallback(fn (): Select => $this->createSelect());
    }

    public function testBeforeLoadAddsStoreJoinAndAggregatedColumns(): void
    {
        $select = $this->createSelect();
        $select->from(['main_table' => 'emico_attributelanding_page'], ['page_id']);

        $subject = $this->createSubject($select);
        $subject->beforeLoad();

        $from = $select->getPart(Select::FROM);
        $columns = $select->getPart(Select::COLUMNS);

        self::assertArrayHasKey('emico_attributelanding_page_store', $from);
        self::assertSame(['main_table.page_id'], $select->getPart(Select::GROUP));
        self::assertCount(3, $columns);
        self::assertSame('store_urls', $columns[1][2]);
        self::assertInstanceOf(Zend_Db_Expr::class, $columns[1][1]);
        self::assertSame('name', $columns[2][2]);
        self::assertInstanceOf(Zend_Db_Expr::class, $columns[2][1]);
    }

    public function testAddFieldToFilterAddsHavingForStoreUrls(): void
    {
        $select = $this->createSelect();
        $subject = $this->createSubject($select);

        $subject->addFieldToFilter('store_urls', ['like' => '%default/url%']);

        $having = implode(' ', $select->getPart(Select::HAVING));

        self::assertStringContainsString('GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id', $having);
        self::assertStringContainsString("'%default/url%'", $having);
    }

    public function testAddFieldToFilterAddsHavingForName(): void
    {
        $select = $this->createSelect();
        $subject = $this->createSubject($select);

        $subject->addFieldToFilter('name', ['like' => '%Landing Page%']);

        $having = implode(' ', $select->getPart(Select::HAVING));

        self::assertStringContainsString('GROUP_CONCAT(DISTINCT CONCAT(emico_attributelanding_page_store.store_id', $having);
        self::assertStringContainsString('emico_attributelanding_page_store.name', $having);
        self::assertStringContainsString("'%Landing Page%'", $having);
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

        self::assertInstanceOf(Select::class, $innerSelect);
        self::assertArrayHasKey('emico_attributelanding_page_store', $innerSelect->getPart(Select::FROM));
        self::assertSame(['main_table.page_id'], $innerSelect->getPart(Select::GROUP));
    }

    private function createSubject(Select $select): TestGridCollection
    {
        return new TestGridCollection($select, $this->connection, $this->eventManager);
    }

    private function createSelect(): Select
    {
        return new Select($this->connection, $this->selectRenderer);
    }
}

class TestGridCollection extends GridCollection
{
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
}
