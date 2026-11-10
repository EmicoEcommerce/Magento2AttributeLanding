<?php

/**
 * @noinspection PhpMethodNamingConventionInspection
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection PhpPropertyNamingConventionInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Model\ResourceModel\Page as PageResourceModel;
use Exception;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * @SuppressWarnings("PHPMD.ExcessivePublicCount")
 * @SuppressWarnings("PHPMD.ExcessiveClassComplexity")
 */
class LandingPage extends AbstractExtensibleModel implements LandingPageInterface
{
    public const CACHE_TAG = 'emico_attributelanding_page';
    /** @var string[] */
    private const ALLOWED_ROBOTS_DIRECTIVES = [
        'INDEX,FOLLOW',
        'NOINDEX,FOLLOW',
        'INDEX,NOFOLLOW',
        'NOINDEX,NOFOLLOW',
    ];
    protected $_eventPrefix = 'emico_attributelanding_page';

    /**
     * LandingPage constructor.
     *
     * @param Context                    $context
     * @param Registry                   $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory      $customAttributeFactory
     * @param Config                     $config
     * @param AbstractResource|null      $resource
     * @param AbstractDb|null            $resourceCollection
     * @param array                      $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        protected Config $config,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = [],
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data,
        );
    }

    /**
     * Initialize resource model
     *
     * @return void
     * @throws LocalizedException
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function _construct()
    {
        $this->_init(PageResourceModel::class);
    }

    /**
     * Get page_id
     *
     * @return int
     */
    public function getPageId(): int
    {
        return (int) $this->getData(static::PAGE_ID);
    }

    /**
     * Set page_id
     *
     * @param int $pageId
     *
     * @return static
     */
    public function setPageId(int $pageId): static
    {
        return $this->setData(static::PAGE_ID, $pageId);
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(static::ACTIVE);
    }

    /**
     * Set active
     *
     * @param string|null $active
     *
     * @return static
     */
    public function setActive(?string $active): static
    {
        return $this->setData(static::ACTIVE, $active);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return LandingPageExtensionInterface|null
     */
    public function getExtensionAttributes(): ?LandingPageExtensionInterface
    {
        /** @phpstan-ignore-next-line */
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param LandingPageExtensionInterface $extensionAttributes
     *
     * @return static
     */
    public function setExtensionAttributes(LandingPageExtensionInterface $extensionAttributes): static
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(static::NAME);
    }

    /**
     * Set name
     *
     * @param string|null $name
     *
     *
     * @return static
     */
    public function setName(?string $name): static
    {
        return $this->setData(static::NAME, $name);
    }

    /**
     * Get url_path
     *
     * @return string|null
     */
    public function getUrlPath(): ?string
    {
        return $this->getData(static::URL_PATH);
    }

    /**
     * Set url_path
     *
     * @param string|null $urlPath
     *
     * @return static
     */
    public function setUrlPath(?string $urlPath): static
    {
        return $this->setData(static::URL_PATH, $urlPath);
    }

    /**
     * Get category_id
     *
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        $categoryId = $this->getData(static::CATEGORY_ID);

        return $categoryId === null ? null : (int) $categoryId;
    }

    /**
     * Set category_id
     *
     * @param int|null $categoryId
     *
     * @return static
     */
    public function setCategoryId(?int $categoryId): static
    {
        return $this->setData(static::CATEGORY_ID, $categoryId);
    }

    /**
     * Get heading
     *
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return $this->getData(static::HEADING);
    }

    /**
     * Set heading
     *
     * @param string|null $heading
     *
     * @return static
     */
    public function setHeading(?string $heading): static
    {
        return $this->setData(static::HEADING, $heading);
    }

    /**
     * Get header_image
     *
     * @return string|null
     */
    public function getHeaderImage(): ?string
    {
        return $this->getData(static::HEADER_IMAGE);
    }

    /**
     * Set header_image
     *
     * @param string|null $headerImage
     *
     * @return static
     */
    public function setHeaderImage(?string $headerImage): static
    {
        return $this->setData(static::HEADER_IMAGE, $headerImage);
    }

    /**
     * Get meta_title
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->getData(static::META_TITLE);
    }

    /**
     * Set meta_title
     *
     * @param string|null $metaTitle
     *
     * @return static
     */
    public function setMetaTitle(?string $metaTitle): static
    {
        return $this->setData(static::META_TITLE, $metaTitle);
    }

    /**
     * Get meta_keywords
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->getData(static::META_KEYWORDS);
    }

    /**
     * Set meta_keywords
     *
     * @param string|null $metaKeywords
     *
     * @return static
     */
    public function setMetaKeywords(?string $metaKeywords): static
    {
        return $this->setData(static::META_KEYWORDS, $metaKeywords);
    }

    /**
     * Get meta_description
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->getData(static::META_DESCRIPTION);
    }

    /**
     * Set meta_description
     *
     * @param string|null $metaDescription
     *
     * @return static
     */
    public function setMetaDescription(?string $metaDescription): static
    {
        return $this->setData(static::META_DESCRIPTION, $metaDescription);
    }

    /**
     * @return string
     */
    public function getRobots(): string
    {
        return $this->normalizeRobotsDirective((string) $this->getData(static::ROBOTS));
    }

    /**
     * @param string|null $robots
     *
     * @return LandingPageInterface
     */
    public function setRobots(?string $robots): LandingPageInterface
    {
        return $this->setData(static::ROBOTS, $this->normalizeRobotsDirective($robots ?? ''));
    }

    /**
     * Get content_first
     *
     * @return string|null
     */
    public function getContentFirst(): ?string
    {
        return $this->getData(static::CONTENT_FIRST);
    }

    /**
     * Set content_first
     *
     * @param string|null $contentFirst
     *
     * @return static
     */
    public function setContentFirst(?string $contentFirst): static
    {
        return $this->setData(static::CONTENT_FIRST, $contentFirst);
    }

    /**
     * Get content_last
     *
     * @return string|null
     */
    public function getContentLast(): ?string
    {
        return $this->getData(static::CONTENT_LAST);
    }

    /**
     * Set content_last
     *
     * @param string|null $contentLast
     *
     * @return static
     */
    public function setContentLast(?string $contentLast): static
    {
        return $this->setData(static::CONTENT_LAST, $contentLast);
    }

    /**
     * Get filter_attributes
     *
     * @return string|null
     */
    public function getFilterAttributes(): ?string
    {
        return $this->getData(static::FILTER_ATTRIBUTES);
    }

    /**
     * Set filter_attributes
     *
     * @param string|null $filterAttributes
     *
     * @return static
     */
    public function setFilterAttributes(?string $filterAttributes): static
    {
        return $this->setData(static::FILTER_ATTRIBUTES, $filterAttributes);
    }

    /**
     * @return array
     * phpcs:disable Magento2.Security.InsecureFunction.FoundWithAlternative
     */
    public function getUnserializedFilterAttributes(): array
    {
        $raw = $this->getFilterAttributes();
        if ($raw === null || $raw === '') {
            return [];
        }

        try {
            $unserialize = unserialize($raw, ['allowed_classes' => false]);
            /* @phpstan-ignore catch.neverThrown  */
        } catch (Exception $e) {
            return [];
        }

        if (!is_array($unserialize)) {
            return [];
        }

        return $unserialize;
    }

    /**
     * @return array
     * @deprecated Use getUnserializedFilterAttributes() instead
     */
    public function getFrontendFilterAttributes(): array
    {
        return $this->getUnserializedFilterAttributes();
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return array_map(fn(array $unserializedFilter) => new Filter(
            $unserializedFilter['attribute'],
            $this->normalizeFilterValues($unserializedFilter['value']),
        ), $this->getUnserializedFilterAttributes());
    }

    /**
     * Get tweakwise_filter_template
     *
     * @return int|null
     */
    public function getTweakwiseFilterTemplate(): ?int
    {
        return $this->getData(static::TWEAKWISE_FILTER_TEMPLATE);
    }

    /**
     * Set tweakwise_filter_template
     *
     * @param string|null $tweakwiseFilterTemplate
     *
     * @return static
     */
    public function setTweakwiseFilterTemplate(?string $tweakwiseFilterTemplate): static
    {
        return $this->setData(static::TWEAKWISE_FILTER_TEMPLATE, $tweakwiseFilterTemplate);
    }

    /**
     * Get tweakwise_filter_template
     *
     * @return int|null
     */
    public function getTweakwiseSortTemplate(): ?int
    {
        return $this->getData(static::TWEAKWISE_SORT_TEMPLATE);
    }

    /**
     * Set tweakwise_filter_template
     *
     * @param string|null $tweakwiseSortTemplate
     *
     * @return static
     */
    public function setTweakwiseSortTemplate(?string $tweakwiseSortTemplate): static
    {
        return $this->setData(static::TWEAKWISE_SORT_TEMPLATE, $tweakwiseSortTemplate);
    }

    /**
     * Get tweakwise_builder_template
     *
     * @return string|null
     */
    public function getTweakwiseBuilderTemplate(): ?string
    {
        return $this->getData(static::TWEAKWISE_BUILDER_TEMPLATE);
    }

    /**
     * Set tweakwise_builder_template
     *
     * @param string|null $tweakwiseBuilderTemplate
     *
     * @return static
     */
    public function setTweakwiseBuilderTemplate(?string $tweakwiseBuilderTemplate): static
    {
        return $this->setData(static::TWEAKWISE_BUILDER_TEMPLATE, $tweakwiseBuilderTemplate);
    }

    /**
     * Get active store ID
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(static::STORE_ID);
    }

    /**
     * @param int $storeId
     *
     * @return static
     */
    public function setStoreId(int $storeId): static
    {
        return $this->setData(static::STORE_ID, $storeId);
    }

    /**
     * @return string
     */
    public function getUrlRewriteEntityType(): string
    {
        return 'landingpage';
    }

    /**
     * @return int
     */
    public function getUrlRewriteEntityId(): int
    {
        return $this->getPageId();
    }

    /**
     * @return string
     */
    public function getUrlRewriteTargetPath(): string
    {
        return sprintf('emico_attributelanding/landingPage/view/id/%d', $this->getPageId());
    }

    /**
     * @return string
     */
    public function getUrlRewriteRequestPath(): string
    {
        $urlPath = $this->getUrlPath();
        if ($this->config->isAppendCategoryUrlSuffix()) {
            $urlPath .= $this->config->getCategoryUrlSuffix();
        }

        return $urlPath;
    }

    /**
     * @return int
     */
    public function getOverviewPageId(): int
    {
        return $this->getData(static::OVERVIEW_PAGE_ID);
    }

    /**
     * @param string|null $overviewPageId
     *
     * @return static
     */
    public function setOverviewPageId(?string $overviewPageId): static
    {
        if ($overviewPageId === '') {
            $overviewPageId = null;
        }

        return $this->setData(static::OVERVIEW_PAGE_ID, $overviewPageId);
    }

    /**
     * @return string|null
     */
    public function getOverviewPageImage(): ?string
    {
        return $this->getData(static::OVERVIEW_PAGE_IMAGE);
    }

    /**
     * @param string|null $overviewPageImage
     *
     * @return static
     */
    public function setOverviewPageImage(?string $overviewPageImage): static
    {
        return $this->setData(static::OVERVIEW_PAGE_IMAGE, $overviewPageImage);
    }

    /**
     * @return bool
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
     */
    public function getIsFilterLinkAllowed(): bool
    {
        return (bool) $this->getData(static::FILTER_LINK_ALLOWED);
    }

    /**
     * @param bool $isFilterLinkAllowed
     *
     * @return static
     */
    public function setIsFilterLinkAllowed(bool $isFilterLinkAllowed = true): static
    {
        return $this->setData(static::FILTER_LINK_ALLOWED, $isFilterLinkAllowed);
    }

    /**
     * @return bool
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
     */
    public function getHideSelectedFilters(): bool
    {
        return (bool) $this->getData(static::HIDE_SELECTED_FILTERS);
    }

    /**
     * @param bool $hideSelectedFilters
     *
     * @return static
     */
    public function setHideSelectedFilters(bool $hideSelectedFilters = true): static
    {
        return $this->setData(static::HIDE_SELECTED_FILTERS, $hideSelectedFilters);
    }

    /**
     * @return string|null
     */
    public function getCanonicalUrl(): ?string
    {
        return $this->getData(static::CANONICAL_URL);
    }

    /**
     * @param string|null $canonicalUrl
     *
     * @return static
     */
    public function setCanonicalUrl(?string $canonicalUrl): static
    {
        return $this->setData(static::CANONICAL_URL, $canonicalUrl);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(static::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(static::UPDATED_AT);
    }

    /**
     * @return array
     */
    public function getLandingPageDataWithoutStore(): array
    {
        $fields = [
            static::PAGE_ID,
            static::CREATED_AT,
            static::UPDATED_AT,
            static::OVERVIEW_PAGE_ID,
            static::OVERVIEW_PAGE_IMAGE,
            static::URL_PATH,
        ];

        if ((int) $this->getData(static::STORE_ID) === 0) {
            $fields[] = static::NAME;
        }

        return array_combine(
            $fields,
            array_map(fn($field) => $this->getData($field), $fields),
        );
    }

    /**
     * @return array
     */
    public function getLandingPageDataForStore(): array
    {
        $fields = [
            static::ACTIVE,
            static::STORE_ID,
            static::NAME,
            static::URL_PATH,
            static::CATEGORY_ID,
            static::HEADING,
            static::HEADER_IMAGE,
            static::META_TITLE,
            static::META_KEYWORDS,
            static::META_DESCRIPTION,
            static::ROBOTS,
            static::CONTENT_FIRST,
            static::CONTENT_LAST,
            static::FILTER_ATTRIBUTES,
            static::TWEAKWISE_FILTER_TEMPLATE,
            static::TWEAKWISE_SORT_TEMPLATE,
            static::TWEAKWISE_BUILDER_TEMPLATE,
            static::FILTER_LINK_ALLOWED,
            static::HIDE_SELECTED_FILTERS,
            static::CANONICAL_URL,
        ];

        $storeData = array_combine(
            $fields,
            array_map(fn($field) => $this->getData($field), $fields),
        );

        $storeData[static::ROBOTS] = $this->getRobots();

        return $storeData;
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @param mixed $value
     *
     * @return string[]
     */
    private function normalizeFilterValues(mixed $value): array
    {
        if (is_array($value)) {
            return array_values($value);
        }

        if (is_string($value) && $value !== '') {
            return [$value];
        }

        return [];
    }

    /**
     * @param string $robotsDirective
     *
     * @return string
     */
    private function normalizeRobotsDirective(string $robotsDirective): string
    {
        if (in_array($robotsDirective, self::ALLOWED_ROBOTS_DIRECTIVES, true)) {
            return $robotsDirective;
        }

        return static::DEFAULT_ROBOTS;
    }
}
