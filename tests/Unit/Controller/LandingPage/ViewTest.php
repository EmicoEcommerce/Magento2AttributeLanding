<?php

declare(strict_types=1);

namespace Tweakwise\Test\Unit\Controller\LandingPage;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Controller\LandingPage\View;
use Emico\AttributeLanding\Model\FilterApplier\FilterApplierInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    private const STORE_ID = 1;
    private const ROOT_CATEGORY_ID = 2;

    public function testRegistersConfiguredCategory(): void
    {
        $category = $this->createStub(CategoryInterface::class);
        $categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $categoryRepository->expects($this->once())
            ->method('get')
            ->with(15)
            ->willReturn($category);

        $registry = $this->createMock(Registry::class);
        $registry->expects($this->once())
            ->method('register')
            ->with('current_category', $category);

        $this->createSubject($this->createLandingPage(15), $categoryRepository, $registry)->execute();
    }

    /**
     * Category layout blocks (e.g. MageWorx SocialMarkup) assume a current category is registered,
     * so a landing page without a category must still register one.
     */
    #[DataProvider('emptyCategoryIdProvider')]
    public function testFallsBackToStoreRootCategoryWhenPageHasNoCategory(mixed $categoryId): void
    {
        $rootCategory = $this->createStub(CategoryInterface::class);
        $categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
        $categoryRepository->expects($this->once())
            ->method('get')
            ->with(self::ROOT_CATEGORY_ID)
            ->willReturn($rootCategory);

        $registry = $this->createMock(Registry::class);
        $registry->expects($this->once())
            ->method('register')
            ->with('current_category', $rootCategory);

        $this->createSubject($this->createLandingPage($categoryId), $categoryRepository, $registry)->execute();
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function emptyCategoryIdProvider(): array
    {
        return [
            'null' => [null],
            'zero' => [0],
            'zero string' => ['0'],
        ];
    }

    private function createLandingPage(mixed $categoryId): LandingPageInterface
    {
        $landingPage = $this->createStub(LandingPageInterface::class);
        $landingPage->method('isActive')->willReturn(true);
        $landingPage->method('getCategoryId')->willReturn($categoryId);

        return $landingPage;
    }

    private function createSubject(
        LandingPageInterface $landingPage,
        CategoryRepositoryInterface $categoryRepository,
        Registry $registry
    ): View {
        $request = $this->createStub(RequestInterface::class);
        $request->method('getParam')->willReturnMap([['id', null, '42']]);

        $context = $this->createStub(Context::class);
        $context->method('getRequest')->willReturn($request);

        $store = $this->createStub(Store::class);
        $store->method('getId')->willReturn(self::STORE_ID);
        $store->method('getRootCategoryId')->willReturn(self::ROOT_CATEGORY_ID);

        $storeManager = $this->createStub(StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);

        $landingPageRepository = $this->createStub(LandingPageRepositoryInterface::class);
        $landingPageRepository->method('getByIdWithStore')
            ->willReturnMap([[42, self::STORE_ID, $landingPage]]);

        $pageFactory = $this->createStub(PageFactory::class);
        $pageFactory->method('create')->willReturn($this->createStub(Page::class));

        return new View(
            $context,
            $pageFactory,
            $this->createStub(LandingPageContext::class),
            $registry,
            $landingPageRepository,
            $categoryRepository,
            $this->createStub(FilterApplierInterface::class),
            $storeManager
        );
    }
}
