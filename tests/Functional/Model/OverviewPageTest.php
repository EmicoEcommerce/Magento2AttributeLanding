<?php

declare(strict_types=1);

namespace Emico\AttributeLandingTest\Functional\Model;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Model\OverviewPage;
use Emico\CodeCept\Test\Unit;
use Emico\AttributeLandingTest\Support\FunctionalTester;

class OverviewPageTest extends Unit
{
    protected FunctionalTester $tester;

    public function testRobotsDefaultsToStoreConfiguration(): void
    {
        $subject = $this->createSubject();

        $this->tester->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $subject->getRobots());
        $this->tester->assertSame(
            OverviewPageInterface::DEFAULT_ROBOTS,
            $subject->getOverviewPageDataForStore()[OverviewPageInterface::ROBOTS]
        );
    }

    public function testRobotsDirectiveIsStoredWithStoreData(): void
    {
        $subject = $this->createSubject();
        $subject->setRobots('NOINDEX,NOFOLLOW');

        $this->tester->assertSame('NOINDEX,NOFOLLOW', $subject->getRobots());
        $this->tester->assertSame(
            'NOINDEX,NOFOLLOW',
            $subject->getOverviewPageDataForStore()[OverviewPageInterface::ROBOTS]
        );
    }

    public function testInvalidRobotsDirectiveUsesDefault(): void
    {
        $subject = $this->createSubject();
        $subject->setRobots('INVALID');

        $this->tester->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $subject->getRobots());
    }

    public function testNullRobotsDirectiveUsesDefault(): void
    {
        $subject = $this->createSubject();
        $subject->setRobots(null);

        $this->tester->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $subject->getRobots());
    }

    private function createSubject(): OverviewPage
    {
        /** @var OverviewPage $subject */
        $subject = $this->tester->getObjectManager()->create(OverviewPage::class);

        return $subject;
    }
}
