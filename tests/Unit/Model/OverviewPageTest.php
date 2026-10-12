<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Model;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Model\OverviewPage;
use Emico\CodeCept\Test\Unit;
use Tweakwise\Test\Support\UnitTester;

class OverviewPageTest extends Unit
{
    protected UnitTester $tester;

    public function testRobotsDefaultsToIndexFollow(): void
    {
        $subject = $this->createSubject();

        $this->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $subject->getRobots());
        $this->assertSame(
            OverviewPageInterface::DEFAULT_ROBOTS,
            $subject->getOverviewPageDataForStore()[OverviewPageInterface::ROBOTS]
        );
    }

    public function testRobotsDirectiveIsStoredWithStoreData(): void
    {
        $subject = $this->createSubject();
        $subject->setRobots('NOINDEX,NOFOLLOW');

        $this->assertSame('NOINDEX,NOFOLLOW', $subject->getRobots());
        $this->assertSame(
            'NOINDEX,NOFOLLOW',
            $subject->getOverviewPageDataForStore()[OverviewPageInterface::ROBOTS]
        );
    }

    public function testInvalidRobotsDirectiveUsesDefault(): void
    {
        $subject = $this->createSubject();
        $subject->setRobots('INVALID');

        $this->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $subject->getRobots());
    }

    public function testNullRobotsDirectiveUsesDefault(): void
    {
        $subject = $this->createSubject();
        $subject->setRobots(null);

        $this->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $subject->getRobots());
    }

    private function createSubject(): OverviewPage
    {
        /** @var OverviewPage $subject */
        $subject = $this->tester->getObjectManager()->create(OverviewPage::class);

        return $subject;
    }
}
