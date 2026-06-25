<?php

declare(strict_types=1);

namespace Tweakwise\Test\Functional\Model;

use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Model\LandingPage;
use Emico\CodeCept\Test\Unit;
use Mockery;

class LandingPageTest extends Unit
{
    private LandingPage $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->tester->getObjectManager()->get(LandingPage::class);
    }

    public function testGetExtensionAttributesReturnsFactoryValueWhenNotSet(): void
    {
        $this->assertInstanceOf(LandingPageExtensionInterface::class, $this->subject->getExtensionAttributes());
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
