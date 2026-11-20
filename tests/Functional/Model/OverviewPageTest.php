<?php

/**
 * @noinspection PhpMethodNamingConventionInspection
 * @noinspection PhpMissingParentCallCommonInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLandingTest\Functional\Model;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Model\OverviewPage;
use Emico\CodeCept\Test\Unit;
use Emico\AttributeLandingTest\Support\FunctionalTester;

class OverviewPageTest extends Unit
{
    protected FunctionalTester $tester;
    private OverviewPage $subject;

    protected function _before(): void
    {
        $this->subject = $this->tester->getObjectManager()->create(OverviewPage::class);
    }

    public function testRobotsDefaultsToStoreConfiguration(): void
    {
        $this->tester->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $this->subject->getRobots());
        $this->tester->assertSame(
            OverviewPageInterface::DEFAULT_ROBOTS,
            $this->subject->getOverviewPageDataForStore()[OverviewPageInterface::ROBOTS]
        );
    }

    public function testRobotsDirectiveIsStoredWithStoreData(): void
    {
        $this->subject->setRobots('NOINDEX,NOFOLLOW');

        $this->tester->assertSame('NOINDEX,NOFOLLOW', $this->subject->getRobots());
        $this->tester->assertSame(
            'NOINDEX,NOFOLLOW',
            $this->subject->getOverviewPageDataForStore()[OverviewPageInterface::ROBOTS]
        );
    }

    public function testInvalidRobotsDirectiveUsesDefault(): void
    {
        $this->subject->setRobots('INVALID');

        $this->tester->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $this->subject->getRobots());
    }

    public function testNullRobotsDirectiveUsesDefault(): void
    {
        $this->subject->setRobots(null);

        $this->tester->assertSame(OverviewPageInterface::DEFAULT_ROBOTS, $this->subject->getRobots());
    }
}
