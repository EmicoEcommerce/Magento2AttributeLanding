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
        return (int) $this->getData(self::PAGE_ID);
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
        return $this->setData(self::PAGE_ID, $pageId);
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(self::ACTIVE);
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
        return $this->setData(self::ACTIVE, $active);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return LandingPageExtensionInterface|null
     */
    public function getExtensionAttributes(): ?LandingPageExtensionInterface
    {
        /** @var LandingPageExtensionInterface|null $extensionAttributes */
        $extensionAttributes = $this->_getExtensionAttributes();

        return $extensionAttributes;
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
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     *
     * @param string|null $name
     *
     * @return static
     */
    public function setName(?string $name): static
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get url_path
     *
     * @return string|null
     */
    public function getUrlPath(): ?string
    {
        return $this->getData(self::URL_PATH);
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
        return $this->setData(self::URL_PATH, $urlPath);
    }

    /**
     * Get category_id
     *
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        $categoryId = $this->getData(self::CATEGORY_ID);

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
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }

    /**
     * Get heading
     *
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return $this->getData(self::HEADING);
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
        return $this->setData(self::HEADING, $heading);
    }

    /**
     * Get header_image
     *
     * @return string|null
     */
    public function getHeaderImage(): ?string
    {
        return $this->getData(self::HEADER_IMAGE);
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
        return $this->setData(self::HEADER_IMAGE, $headerImage);
    }

    /**
     * Get meta_title
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->getData(self::META_TITLE);
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
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * Get meta_keywords
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->getData(self::META_KEYWORDS);
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
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * Get meta_description
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->getData(self::META_DESCRIPTION);
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
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Get content_first
     *
     * @return string|null
     */
    public function getContentFirst(): ?string
    {
        return $this->getData(self::CONTENT_FIRST);
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
        return $this->setData(self::CONTENT_FIRST, $contentFirst);
    }

    /**
     * Get content_last
     *
     * @return string|null
     */
    public function getContentLast(): ?string
    {
        return $this->getData(self::CONTENT_LAST);
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
        return $this->setData(self::CONTENT_LAST, $contentLast);
    }

    /**
     * Get filter_attributes
     *
     * @return string|null
     */
    public function getFilterAttributes(): ?string
    {
        return $this->getData(self::FILTER_ATTRIBUTES);
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
        return $this->setData(self::FILTER_ATTRIBUTES, $filterAttributes);
    }

    /**
     * @return array
     * phpcs:disable Magento2.Security.InsecureFunction.FoundWithAlternative
     */
    public function getUnserializedFilterAttributes(): array
    {
        if ($this->getFilterAttributes() === null) {
            return [];
        }

        $unserialize = unserialize($this->getFilterAttributes());

        if (!is_array($unserialize)) {
            return [];
        }

        return $unserialize;
    }

    /**
     * @return array
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
        return array_map(
            fn(array $unserializedFilter) => new Filter($unserializedFilter['attribute'], $unserializedFilter['value']),
            $this->getFrontendFilterAttributes(),
        );
    }

    /**
     * Get tweakwise_filter_template
     *
     * @return int|null
     */
    public function getTweakwiseFilterTemplate(): ?int
    {
        return $this->getData(self::TWEAKWISE_FILTER_TEMPLATE);
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
        return $this->setData(self::TWEAKWISE_FILTER_TEMPLATE, $tweakwiseFilterTemplate);
    }

    /**
     * Get tweakwise_filter_template
     *
     * @return int|null
     */
    public function getTweakwiseSortTemplate(): ?int
    {
        return $this->getData(self::TWEAKWISE_SORT_TEMPLATE);
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
        return $this->setData(self::TWEAKWISE_SORT_TEMPLATE, $tweakwiseSortTemplate);
    }

    /**
     * Get tweakwise_builder_template
     *
     * @return string|null
     */
    public function getTweakwiseBuilderTemplate(): ?string
    {
        return $this->getData(self::TWEAKWISE_BUILDER_TEMPLATE);
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
        return $this->setData(self::TWEAKWISE_BUILDER_TEMPLATE, $tweakwiseBuilderTemplate);
    }

    /**
     * Get active store ID
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @param int $storeId
     *
     * @return static
     */
    public function setStoreId(int $storeId): static
    {
        return $this->setData(self::STORE_ID, $storeId);
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
        return $this->getData(LandingPageInterface::OVERVIEW_PAGE_ID);
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

        return $this->setData(LandingPageInterface::OVERVIEW_PAGE_ID, $overviewPageId);
    }

    /**
     * @return string|null
     */
    public function getOverviewPageImage(): ?string
    {
        return $this->getData(LandingPageInterface::OVERVIEW_PAGE_IMAGE);
    }

    /**
     * @param string|null $overviewPageImage
     *
     * @return static
     */
    public function setOverviewPageImage(?string $overviewPageImage): static
    {
        return $this->setData(LandingPageInterface::OVERVIEW_PAGE_IMAGE, $overviewPageImage);
    }

    /**
     * @return bool
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
     */
    public function getIsFilterLinkAllowed(): bool
    {
        return (bool) $this->getData(LandingPageInterface::FILTER_LINK_ALLOWED);
    }

    /**
     * @param bool $isFilterLinkAllowed
     *
     * @return static
     */
    public function setIsFilterLinkAllowed(bool $isFilterLinkAllowed = true): static
    {
        return $this->setData(LandingPageInterface::FILTER_LINK_ALLOWED, $isFilterLinkAllowed);
    }

    /**
     * @return bool
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
     */
    public function getHideSelectedFilters(): bool
    {
        return (bool) $this->getData(LandingPageInterface::HIDE_SELECTED_FILTERS);
    }

    /**
     * @param bool $hideSelectedFilters
     *
     * @return static
     */
    public function setHideSelectedFilters(bool $hideSelectedFilters = true): static
    {
        return $this->setData(LandingPageInterface::HIDE_SELECTED_FILTERS, $hideSelectedFilters);
    }

    /**
     * @return string|null
     */
    public function getCanonicalUrl(): ?string
    {
        return $this->getData(LandingPageInterface::CANONICAL_URL);
    }

    /**
     * @param string|null $canonicalUrl
     *
     * @return static
     */
    public function setCanonicalUrl(?string $canonicalUrl): static
    {
        return $this->setData(LandingPageInterface::CANONICAL_URL, $canonicalUrl);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(LandingPageInterface::CREATED_AT);
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(LandingPageInterface::UPDATED_AT);
    }

    /**
     * @return array
     */
    public function getLandingPageDataWithoutStore(): array
    {
        $fields = [
            LandingPageInterface::PAGE_ID,
            LandingPageInterface::CREATED_AT,
            LandingPageInterface::UPDATED_AT,
            LandingPageInterface::OVERVIEW_PAGE_ID,
            LandingPageInterface::OVERVIEW_PAGE_IMAGE,
            LandingPageInterface::URL_PATH,
        ];

        if ((int) $this->getData(LandingPageInterface::STORE_ID) === 0) {
            $fields[] = LandingPageInterface::NAME;
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
            LandingPageInterface::ACTIVE,
            LandingPageInterface::STORE_ID,
            LandingPageInterface::NAME,
            LandingPageInterface::URL_PATH,
            LandingPageInterface::CATEGORY_ID,
            LandingPageInterface::HEADING,
            LandingPageInterface::HEADER_IMAGE,
            LandingPageInterface::META_TITLE,
            LandingPageInterface::META_KEYWORDS,
            LandingPageInterface::META_DESCRIPTION,
            LandingPageInterface::CONTENT_FIRST,
            LandingPageInterface::CONTENT_LAST,
            LandingPageInterface::FILTER_ATTRIBUTES,
            LandingPageInterface::TWEAKWISE_FILTER_TEMPLATE,
            LandingPageInterface::TWEAKWISE_SORT_TEMPLATE,
            LandingPageInterface::TWEAKWISE_BUILDER_TEMPLATE,
            LandingPageInterface::FILTER_LINK_ALLOWED,
            LandingPageInterface::HIDE_SELECTED_FILTERS,
            LandingPageInterface::CANONICAL_URL,
        ];

        return array_combine(
            $fields,
            array_map(fn($field) => $this->getData($field), $fields),
        );
    }
}
