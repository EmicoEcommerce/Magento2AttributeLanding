<?php

declare(strict_types=1);

namespace Tweakwise\Test\Functional\Model;

use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Model\LandingPage;
use Emico\CodeCept\Test\Unit;

class LandingPageTest extends Unit
{
    private LandingPage $subject;

    protected function _before(): void
    {
        $this->subject = $this->tester->getObjectManager()->get(LandingPage::class);
    }

    public function testGetExtensionAttributesReturnsFactoryValueWhenNotSet(): void
    {
        $this->tester->assertInstanceOf(LandingPageExtensionInterface::class, $this->subject->getExtensionAttributes());
    }

    public function testSetAndGetExtensionAttributesRoundTrip(): void
    {
        $extensionAttributes = $this->createMock(LandingPageExtensionInterface::class);

        $result = $this->subject->setExtensionAttributes($extensionAttributes);

        $this->tester->assertSame($this->subject, $result);
        $this->tester->assertSame($extensionAttributes, $this->subject->getExtensionAttributes());
    }
}
