<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model;

use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Model\Config;
use Emico\AttributeLanding\Model\LandingPage;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\State;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ActionValidator\RemoveAction;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Registry;
use Mockery;
use Mockery\MockInterface;

class LandingPageTest extends Unit
{
    private Context|MockInterface $context;
    private Registry|MockInterface $registry;
    private ExtensionAttributesFactory|MockInterface $extensionFactory;
    private AttributeValueFactory|MockInterface $customAttributeFactory;
    private Config|MockInterface $config;
    private LandingPageExtensionInterface|MockInterface $defaultExtensionAttributes;

    private LandingPage $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = Mockery::mock(Context::class)->makePartial();
        $this->context->shouldReceive('getEventDispatcher')->andReturn(
            Mockery::mock(ManagerInterface::class)->shouldIgnoreMissing()
        );
        $this->context->shouldReceive('getCacheManager')->andReturn(
            Mockery::mock(CacheInterface::class)->shouldIgnoreMissing()
        );
        $this->context->shouldReceive('getAppState')->andReturn(
            Mockery::mock(State::class)->makePartial()
        );
        $this->context->shouldReceive('getActionValidator')->andReturn(
            Mockery::mock(RemoveAction::class)->shouldIgnoreMissing()
        );

        $this->registry = Mockery::mock(Registry::class)->shouldIgnoreMissing();

        $this->extensionFactory = Mockery::mock(ExtensionAttributesFactory::class)->shouldIgnoreMissing();
        $this->defaultExtensionAttributes = Mockery::mock(LandingPageExtensionInterface::class);
        $this->extensionFactory->shouldReceive('create')
            ->with(LandingPage::class, [])
            ->andReturn($this->defaultExtensionAttributes);

        $this->customAttributeFactory = Mockery::mock(AttributeValueFactory::class)->shouldIgnoreMissing();

        $this->config = Mockery::mock(Config::class)->shouldIgnoreMissing();

        $resource = Mockery::mock(AbstractDb::class);
        $resource->shouldReceive('getIdFieldName')->andReturn('page_id');

        $this->subject = new LandingPage(
            $this->context,
            $this->registry,
            $this->extensionFactory,
            $this->customAttributeFactory,
            $this->config,
            $resource
        );
    }

    public function testGetExtensionAttributesReturnsFactoryValueWhenNotSet(): void
    {
        $this->assertSame($this->defaultExtensionAttributes, $this->subject->getExtensionAttributes());
    }

    public function testSetAndGetExtensionAttributesRoundTrip(): void
    {
        $extensionAttributes = Mockery::mock(LandingPageExtensionInterface::class);

        $result = $this->subject->setExtensionAttributes($extensionAttributes);

        $this->assertSame($this->subject, $result);
        $this->assertSame($extensionAttributes, $this->subject->getExtensionAttributes());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
