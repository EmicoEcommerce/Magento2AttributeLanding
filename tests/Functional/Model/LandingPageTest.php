<?php

/**
 * @noinspection PhpMethodNamingConventionInspection
 * @noinspection PhpMissingParentCallCommonInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLandingTest\Functional\Model;

use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Model\LandingPage;
use Emico\AttributeLandingTest\Support\FunctionalTester;
use Emico\CodeCept\Test\Unit;

class LandingPageTest extends Unit
{
    protected FunctionalTester $tester;
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

    public function testGetUnserializedFilterAttributesReturnsEmptyArrayForNull(): void
    {
        $this->tester->assertSame([], $this->subject->getUnserializedFilterAttributes());
    }

    public function testGetUnserializedFilterAttributesReturnsEmptyArrayForEmptyString(): void
    {
        $this->subject->setFilterAttributes('');

        $this->tester->assertSame([], $this->subject->getUnserializedFilterAttributes());
    }

    public function testGetUnserializedFilterAttributesReturnsEmptyArrayForScalarPayload(): void
    {
        $this->subject->setFilterAttributes('s:12:"not-an-array";');

        $this->tester->assertSame([], $this->subject->getUnserializedFilterAttributes());
    }

    public function testGetUnserializedFilterAttributesReturnsArrayForValidSerializedArray(): void
    {
        $expected = [
            ['attribute' => 'color', 'value' => 'green'],
            ['attribute' => 'size', 'value' => 'm'],
        ];
        $this->subject->setFilterAttributes(
            'a:2:{i:0;a:2:{s:9:"attribute";s:5:"color";s:5:"value";s:5:"green";}'
            . 'i:1;a:2:{s:9:"attribute";s:4:"size";s:5:"value";s:1:"m";}}',
        );

        $this->tester->assertSame($expected, $this->subject->getUnserializedFilterAttributes());
    }

    public function testGetFiltersKeepsEveryValueOfAMultiselectFilter(): void
    {
        $this->subject->setFilterAttributes(
            'a:1:{i:0;a:2:{s:9:"attribute";s:5:"color";s:5:"value";'
            . 'a:2:{i:0;s:5:"green";i:1;s:4:"blue";}}}',
        );

        $filters = $this->subject->getFilters();

        $this->tester->assertCount(1, $filters);
        $this->tester->assertSame('color', $filters[0]->getFacet());
        $this->tester->assertSame(['green', 'blue'], $filters[0]->getValues());
    }

    public function testGetFiltersReadsValuesStoredBySingleSelect(): void
    {
        $this->subject->setFilterAttributes(
            'a:1:{i:0;a:2:{s:9:"attribute";s:5:"color";s:5:"value";s:5:"green";}}',
        );

        $filters = $this->subject->getFilters();

        $this->tester->assertCount(1, $filters);
        $this->tester->assertSame(['green'], $filters[0]->getValues());
    }
}
