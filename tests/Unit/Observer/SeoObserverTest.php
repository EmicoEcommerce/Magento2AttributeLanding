<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Observer;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Block\OverviewPage\View as OverviewPageView;
use Emico\AttributeLanding\Model\Config;
use Emico\AttributeLanding\Model\LandingPageContext;
use Emico\AttributeLanding\Observer\SeoObserver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\Request\Http as MagentoHttpRequest;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\View\Page\Title;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SeoObserverTest extends TestCase
{
    public function testStoreConfigurationIsPreservedWhenPageRobotsIsEmpty(): void
    {
        $pageConfig = $this->createPageConfig();
        $pageConfig->expects($this->never())->method('setRobots');

        $this->executeObserver($pageConfig, '');
    }

    public function testPageRobotsOverridesStoreConfigurationWhenConfigured(): void
    {
        $pageConfig = $this->createPageConfig();
        $pageConfig->expects($this->once())
            ->method('setRobots')
            ->with('NOINDEX,NOFOLLOW');

        $this->executeObserver($pageConfig, 'NOINDEX,NOFOLLOW');
    }

    private function createPageConfig(): PageConfig
    {
        /** @var PageConfig&MockObject $pageConfig */
        $pageConfig = $this->createMock(PageConfig::class);
        $pageConfig->method('getTitle')->willReturn($this->createMock(Title::class));

        return $pageConfig;
    }

    private function executeObserver(PageConfig $pageConfig, string $robots): void
    {
        /** @var OverviewPageInterface&MockObject $overviewPage */
        $overviewPage = $this->createMock(OverviewPageInterface::class);
        $overviewPage->method('getRobots')->willReturn($robots);

        /** @var LandingPageContext&MockObject $landingPageContext */
        $landingPageContext = $this->createMock(LandingPageContext::class);
        $landingPageContext->method('getLandingPage')->willReturn(null);
        $landingPageContext->method('getOverviewPage')->willReturn($overviewPage);

        /** @var LayoutInterface&MockObject $layout */
        $layout = $this->createMock(LayoutInterface::class);
        $layout->method('getBlock')->with('page.main.title')->willReturn(false);

        /** @var OverviewPageView&MockObject $block */
        $block = $this->createMock(OverviewPageView::class);
        $block->method('getLayout')->willReturn($layout);

        $observer = new SeoObserver(
            $pageConfig,
            $this->createMock(Config::class),
            $landingPageContext,
            $this->createMock(CategoryRepositoryInterface::class),
            $this->createMock(StoreManagerInterface::class),
            $this->createMock(MagentoHttpRequest::class)
        );

        $observer->execute(new Observer(['block' => $block]));
    }
}
