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
        $subject->setFilterAttributes(serialize('not-an-array'));

        $this->assertSame([], $subject->getUnserializedFilterAttributes());
    }

    public function testGetUnserializedFilterAttributesReturnsArrayForValidSerializedArray(): void
    {
        $subject = $this->createSubject();
        $expected = [
            ['attribute' => 'color', 'value' => 'green'],
            ['attribute' => 'size', 'value' => 'm'],
        ];
        $subject->setFilterAttributes(serialize($expected));

        $this->assertSame($expected, $subject->getUnserializedFilterAttributes());
    }

    private function createSubject(): LandingPage
    {
        /** @var LandingPage $subject */
        $subject = $this->tester->getObjectManager()->create(LandingPage::class);

        return $subject;
    }
}
