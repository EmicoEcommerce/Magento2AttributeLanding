<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model;

use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Model\Config;
use Emico\AttributeLanding\Model\LandingPage;
use Emico\CodeCept\Test\Unit;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Mockery;
use Mockery\MockInterface;
use Tweakwise\Test\Support\UnitTester;

class LandingPageTest extends Unit
{
    protected UnitTester $tester;

    private Context|MockInterface $context;
    private Registry|MockInterface $registry;
    private ExtensionAttributesFactory|MockInterface $extensionFactory;
    private AttributeValueFactory|MockInterface $customAttributeFactory;
    private Config|MockInterface $config;

    private LandingPage $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = Mockery::mock(Context::class)->makePartial();
        $this->context->shouldReceive('getEventDispatcher')->andReturn(
            Mockery::mock(\Magento\Framework\Event\ManagerInterface::class)->shouldIgnoreMissing()
        );
        $this->context->shouldReceive('getCacheManager')->andReturn(
            Mockery::mock(\Magento\Framework\App\CacheInterface::class)->shouldIgnoreMissing()
        );
        $this->context->shouldReceive('getAppState')->andReturn(
            Mockery::mock(\Magento\Framework\App\State::class)->makePartial()
        );
        $this->context->shouldReceive('getActionValidator')->andReturn(
            Mockery::mock(\Magento\Framework\Model\ActionValidator\RemoveAction::class)->shouldIgnoreMissing()
        );

        $this->registry = Mockery::mock(Registry::class)->shouldIgnoreMissing();

        $this->extensionFactory = Mockery::mock(ExtensionAttributesFactory::class)->shouldIgnoreMissing();

        $this->customAttributeFactory = Mockery::mock(AttributeValueFactory::class)->shouldIgnoreMissing();

        $this->config = Mockery::mock(Config::class)->shouldIgnoreMissing();

        $this->subject = new LandingPage(
            $this->context,
            $this->registry,
            $this->extensionFactory,
            $this->customAttributeFactory,
            $this->config
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testGetExtensionAttributesReturnsNullWhenNotSet(): void
    {
        $this->tester->assertNull($this->subject->getExtensionAttributes());
    }

    public function testSetAndGetExtensionAttributesRoundTrip(): void
    {
        $extensionAttributes = Mockery::mock(LandingPageExtensionInterface::class);

        $result = $this->subject->setExtensionAttributes($extensionAttributes);

        $this->tester->assertSame($this->subject, $result);
        $this->tester->assertSame($extensionAttributes, $this->subject->getExtensionAttributes());
    }
}
