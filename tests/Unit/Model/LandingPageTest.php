<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model;

use Emico\AttributeLanding\Model\LandingPage;
use Emico\CodeCept\Test\Unit;
use Tweakwise\Test\Support\UnitTester;

class LandingPageTest extends Unit
{
    protected UnitTester $tester;

    public function testGetUnserializedFilterAttributesReturnsEmptyArrayForNull(): void
    {
        $subject = $this->createSubject();

        $this->assertSame([], $subject->getUnserializedFilterAttributes());
    }

    public function testGetUnserializedFilterAttributesReturnsEmptyArrayForEmptyString(): void
    {
        $subject = $this->createSubject();
        $subject->setFilterAttributes('');

        $this->assertSame([], $subject->getUnserializedFilterAttributes());
    }

    public function testGetUnserializedFilterAttributesReturnsEmptyArrayForScalarPayload(): void
    {
        $subject = $this->createSubject();
        $subject->setFilterAttributes('s:12:"not-an-array";');

        $this->assertSame([], $subject->getUnserializedFilterAttributes());
    }

    public function testGetUnserializedFilterAttributesReturnsArrayForValidSerializedArray(): void
    {
        $subject = $this->createSubject();
        $expected = [
            ['attribute' => 'color', 'value' => 'green'],
            ['attribute' => 'size', 'value' => 'm'],
        ];
        $subject->setFilterAttributes(
            'a:2:{i:0;a:2:{s:9:"attribute";s:5:"color";s:5:"value";s:5:"green";}'
            . 'i:1;a:2:{s:9:"attribute";s:4:"size";s:5:"value";s:1:"m";}}'
        );

        $this->assertSame($expected, $subject->getUnserializedFilterAttributes());
    }

    private function createSubject(): LandingPage
    {
        /** @var LandingPage $subject */
        $subject = $this->tester->getObjectManager()->create(LandingPage::class);

        return $subject;
    }
}
