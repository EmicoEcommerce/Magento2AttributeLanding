<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface LandingPageInterface extends ExtensibleDataInterface
{
    /**
     * Field constants
     */
    public const META_TITLE = 'meta_title';
    public const CONTENT_LAST = 'content_last';
    public const ACTIVE = 'active';
    public const HEADING = 'heading';
    public const CONTENT_FIRST = 'content_first';
    public const FILTER_ATTRIBUTES = 'filter_attributes';
    public const NAME = 'name';
    public const URL_PATH = 'url_path';
    public const META_KEYWORDS = 'meta_keywords';
    public const TWEAKWISE_FILTER_TEMPLATE = 'tweakwise_filter_template';
    public const TWEAKWISE_SORT_TEMPLATE = 'tweakwise_sort_template';
    public const TWEAKWISE_BUILDER_TEMPLATE = 'tweakwise_builder_template';
    public const HEADER_IMAGE = 'header_image';
    public const CATEGORY_ID = 'category_id';
    public const PAGE_ID = 'page_id';
    public const META_DESCRIPTION = 'meta_description';
    public const OVERVIEW_PAGE_ID = 'overview_page_id';
    public const OVERVIEW_PAGE_IMAGE = 'overview_page_image';
    public const FILTER_LINK_ALLOWED = 'is_filter_link_allowed';
    public const HIDE_SELECTED_FILTERS = 'hide_selected_filters';
    public const CANONICAL_URL = 'canonical_url';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const STORE_ID = 'store_id';

    /**
     * Set an extension attributes object.
     * @param \Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(LandingPageExtensionInterface $extensionAttributes): LandingPageInterface;

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Get page_id
     * @return int|null
     */
    public function getPageId();

    /**
     * Get store_id
     * @return int|null
     */
    public function getStoreId();

    /**
     * Get active
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Get name
     * @return string|null
     */
    public function getName(): string;

    /**
     * Get url_path
     * @return string|null
     */
    public function getUrlPath();

    /**
     * Get category_id
     * @return int|null
     */
    public function getCategoryId();

    /**
     * Get heading
     * @return string|null
     */
    public function getHeading();

    /**
     * Get header_image
     * @return string|null
     */
    public function getHeaderImage();

    /**
     * Get meta_title
     * @return string|null
     */
    public function getMetaTitle();

    /**
     * Get meta_keywords
     * @return string|null
     */
    public function getMetaKeywords();

    /**
     * Get meta_description
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * Get content_first
     * @return string|null
     */
    public function getContentFirst();

    /**
     * Get content_last
     * @return string|null
     */
    public function getContentLast();

    /**
     * Get tweakwise_filter_template
     * @return int|null
     */
    public function getTweakwiseFilterTemplate();

    /**
     * Get tweakwise_filter_template
     * @return int|null
     */
    public function getTweakwiseSortTemplate();

    /**
     * Get tweakwise_builder_template
     * @return string|null
     */
    public function getTweakwiseBuilderTemplate(): ?string;


    /**
     * Get filter_attributes
     * @return string|null
     */
    public function getFilterAttributes();

    /**
     * Set filter_attributes
     * @param string $filterAttributes
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setFilterAttributes(?string $filterAttributes): LandingPageInterface;

    /**
     * @return \Emico\AttributeLanding\Api\Data\FilterInterface[]
     */
    public function getFilters(): array;

    /**
     * @return mixed
     */
    public function getUnserializedFilterAttributes(): array;

    /**
     * @return int
     */
    public function getOverviewPageId();

    /**
     * @return string
     */
    public function getOverviewPageImage();

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsFilterLinkAllowed(): bool;

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getHideSelectedFilters(): bool;

    /**
     * @return string
     */
    public function getCanonicalUrl();

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param string|null $pageId
     * @return LandingPageInterface
     */
    public function setPageId(?string $pageId): LandingPageInterface;

    public function setStoreId(?int $storeId): LandingPageInterface;

    /**
     * @param string|null $active
     * @return LandingPageInterface
     */
    public function setActive(?string $active): LandingPageInterface;

    /**
     * @param string|null $name
     * @return LandingPageInterface
     */
    public function setName(?string $name): LandingPageInterface;

    /**
     * @param int|null $categoryId
     * @return LandingPageInterface
     */
    public function setCategoryId(?int $categoryId): LandingPageInterface;

    /**
     * @param string|null $heading
     * @return LandingPageInterface
     */
    public function setHeading(?string $heading): LandingPageInterface;

    /**
     * @param string|null $headerImage
     * @return LandingPageInterface
     */
    public function setHeaderImage(?string $headerImage): LandingPageInterface;

    /**
     * @param string|null $metaTitle
     * @return LandingPageInterface
     */
    public function setMetaTitle(?string $metaTitle): LandingPageInterface;

    /**
     * @param string|null $metaKeywords
     * @return LandingPageInterface
     */
    public function setMetaKeywords(?string $metaKeywords): LandingPageInterface;

    /**
     * @param string|null $metaDescription
     * @return LandingPageInterface
     */
    public function setMetaDescription(?string $metaDescription): LandingPageInterface;

    /**
     * @param string|null $contentFirst
     * @return LandingPageInterface
     */
    public function setContentFirst(?string $contentFirst): LandingPageInterface;

    /**
     * @param string|null $contentLast
     * @return LandingPageInterface
     */
    public function setContentLast(?string $contentLast): LandingPageInterface;

    /**
     * @param string|null $tweakwiseFilterTemplate
     * @return LandingPageInterface
     */
    public function setTweakwiseFilterTemplate(?string $tweakwiseFilterTemplate): LandingPageInterface;

    /**
     * @param string|null $tweakwiseSortTemplate
     * @return LandingPageInterface
     */
    public function setTweakwiseSortTemplate(?string $tweakwiseSortTemplate): LandingPageInterface;

    /**
     * @param string|null $tweakwiseBuilderTemplate
     * @return LandingPageInterface
     */
    public function setTweakwiseBuilderTemplate(?string $tweakwiseBuilderTemplate): LandingPageInterface;

    /**
     * @param string|null $overviewPageId
     * @return LandingPageInterface
     */
    public function setOverviewPageId(?string $overviewPageId): LandingPageInterface;

    /**
     * @param string|null $overviewPageImage
     * @return LandingPageInterface
     */
    public function setOverviewPageImage(?string $overviewPageImage): LandingPageInterface;

    /**
     * @param string|null $urlPath
     * @return LandingPageInterface
     */
    public function setUrlPath(?string $urlPath): LandingPageInterface;

    /**
     * @param string|null $canonicalUrl
     * @return LandingPageInterface
     */
    public function setCanonicalUrl(?string $canonicalUrl): LandingPageInterface;

    /**
     * @param bool $isFilterLinkAllowed
     * @return LandingPageInterface
     */
    public function setIsFilterLinkAllowed(bool $isFilterLinkAllowed = true): LandingPageInterface;

    /**
     * @param bool $hideSelectedFilters
     * @return LandingPageInterface
     */
    public function setHideSelectedFilters(bool $hideSelectedFilters = true): LandingPageInterface;
}
