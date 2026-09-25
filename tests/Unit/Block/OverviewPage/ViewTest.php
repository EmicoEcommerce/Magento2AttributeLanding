<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Block\OverviewPage;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Emico\AttributeLanding\Block\OverviewPage\View;
use Emico\CodeCept\Test\Unit;
use Mockery;
use Tweakwise\Test\Support\UnitTester;

class ViewTest extends Unit
{
    protected UnitTester $tester;

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testGetLandingPageUrlUsesUrlRewriteRequestPathWhenLandingPageImplementsInterface(): void
    {
        $landingPage = Mockery::mock(LandingPageInterface::class, UrlRewriteGeneratorInterface::class);
        $landingPage->shouldReceive('getUrlRewriteRequestPath')->andReturn('landing/suffixed-url.html');
        $landingPage->shouldNotReceive('getUrlPath');

        $subject = $this->createSubject();

        $this->assertSame('landing/suffixed-url.html', $subject->getLandingPageUrl($landingPage));
    }

    public function testGetLandingPageUrlFallsBackToUrlPathWhenLandingPageDoesNotImplementInterface(): void
    {
        $landingPage = Mockery::mock(LandingPageInterface::class);
        $landingPage->shouldReceive('getUrlPath')->andReturn('raw/url-path');

        $subject = $this->createSubject();

        $this->assertSame('raw/url-path', $subject->getLandingPageUrl($landingPage));
    }

    private function createSubject(): View
    {
        /** @var View $subject */
        $subject = $this->tester->getObjectManager()->create(View::class);

        return $subject;
    }
}
