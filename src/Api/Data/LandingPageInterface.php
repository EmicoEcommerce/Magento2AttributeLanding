<?php

/**
 * @author        Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 * @noinspection  PhpFullyQualifiedNameUsageInspection
 * @noinspection  PhpUnnecessaryFullyQualifiedNameInspection
 * @phpcs:disable EmicoMagento2.Classes.UnsupportedTypesInApiInterface.UnsupportedTypesInApiInterface
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Api\Data;

use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

interface LandingPageInterface extends ExtensibleDataInterface, UrlRewriteGeneratorInterface
{
    /**
     * Field constants
     */
    public const META_TITLE                 = 'meta_title';
    public const CONTENT_LAST               = 'content_last';
    public const ACTIVE                     = 'active';
    public const HEADING                    = 'heading';
    public const CONTENT_FIRST              = 'content_first';
    public const FILTER_ATTRIBUTES          = 'filter_attributes';
    public const NAME                       = 'name';
    public const URL_PATH                   = 'url_path';
    public const META_KEYWORDS              = 'meta_keywords';
    public const TWEAKWISE_FILTER_TEMPLATE  = 'tweakwise_filter_template';
    public const TWEAKWISE_SORT_TEMPLATE    = 'tweakwise_sort_template';
    public const TWEAKWISE_BUILDER_TEMPLATE = 'tweakwise_builder_template';
    public const HEADER_IMAGE               = 'header_image';
    public const CATEGORY_ID                = 'category_id';
    public const PAGE_ID                    = 'page_id';
    public const META_DESCRIPTION           = 'meta_description';
    public const OVERVIEW_PAGE_ID           = 'overview_page_id';
    public const OVERVIEW_PAGE_IMAGE        = 'overview_page_image';
    public const FILTER_LINK_ALLOWED        = 'is_filter_link_allowed';
    public const HIDE_SELECTED_FILTERS      = 'hide_selected_filters';
    public const CANONICAL_URL              = 'canonical_url';
    public const CREATED_AT                 = 'created_at';
    public const UPDATED_AT                 = 'updated_at';
    public const STORE_ID                   = 'store_id';

    /**
     * Set an extension attributes object.
     *
     * @param \Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface $extensionAttributes
     *
     * @return static
     */
    public function setExtensionAttributes(LandingPageExtensionInterface $extensionAttributes): static;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageExtensionInterface|null
     */
    public function getExtensionAttributes(): ?LandingPageExtensionInterface;

    /**
     * Get store_id
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Get active
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get url_path
     *
     * @return string|null
     */
    public function getUrlPath(): ?string;

    /**
     * Get category_id
     *
     * @return int|null
     */
    public function getCategoryId(): ?int;

    /**
     * Get heading
     *
     * @return string|null
     */
    public function getHeading(): ?string;

    /**
     * Get header_image
     *
     * @return string|null
     */
    public function getHeaderImage(): ?string;

    /**
     * Get meta_title
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * Get meta_keywords
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string;

    /**
     * Get meta_description
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * Get content_first
     *
     * @return string|null
     */
    public function getContentFirst(): ?string;

    /**
     * Get content_last
     *
     * @return string|null
     */
    public function getContentLast(): ?string;

    /**
     * Get tweakwise_filter_template
     *
     * @return int|null
     */
    public function getTweakwiseFilterTemplate(): ?int;

    /**
     * Get tweakwise_filter_template
     *
     * @return int|null
     */
    public function getTweakwiseSortTemplate(): ?int;

    /**
     * Get tweakwise_builder_template
     *
     * @return string|null
     */
    public function getTweakwiseBuilderTemplate(): ?string;

    /**
     * Get filter_attributes
     *
     * @return string|null
     */
    public function getFilterAttributes(): ?string;

    /**
     * Set filter_attributes
     *
     * @param string|null $filterAttributes
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setFilterAttributes(?string $filterAttributes): LandingPageInterface;

    /**
     * @return \Emico\AttributeLanding\Api\Data\FilterInterface[]
     */
    public function getFilters(): array;

    /**
     * @return string[]
     */
    public function getUnserializedFilterAttributes(): array;

    /**
     * @return int
     */
    public function getOverviewPageId(): int;

    /**
     * @return string
     */
    public function getOverviewPageImage(): string;

    /**
     * @return bool
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
     */
    public function getIsFilterLinkAllowed(): bool;

    /**
     * @return bool
     * @SuppressWarnings("PHPMD.BooleanGetMethodName")
     */
    public function getHideSelectedFilters(): bool;

    /**
     * @return string
     */
    public function getCanonicalUrl(): string;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * @param int $storeId
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setStoreId(int $storeId): LandingPageInterface;

    /**
     * @param string|null $active
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setActive(?string $active): LandingPageInterface;

    /**
     * @param string|null $name
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setName(?string $name): LandingPageInterface;

    /**
     * @param int|null $categoryId
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setCategoryId(?int $categoryId): LandingPageInterface;

    /**
     * @param string|null $heading
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setHeading(?string $heading): LandingPageInterface;

    /**
     * @param string|null $headerImage
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setHeaderImage(?string $headerImage): LandingPageInterface;

    /**
     * @param string|null $metaTitle
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaTitle(?string $metaTitle): LandingPageInterface;

    /**
     * @param string|null $metaKeywords
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaKeywords(?string $metaKeywords): LandingPageInterface;

    /**
     * @param string|null $metaDescription
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setMetaDescription(?string $metaDescription): LandingPageInterface;

    /**
     * @param string|null $contentFirst
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setContentFirst(?string $contentFirst): LandingPageInterface;

    /**
     * @param string|null $contentLast
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setContentLast(?string $contentLast): LandingPageInterface;

    /**
     * @param string|null $tweakwiseFilterTemplate
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setTweakwiseFilterTemplate(?string $tweakwiseFilterTemplate): LandingPageInterface;

    /**
     * @param string|null $tweakwiseSortTemplate
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setTweakwiseSortTemplate(?string $tweakwiseSortTemplate): LandingPageInterface;

    /**
     * @param string|null $tweakwiseBuilderTemplate
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setTweakwiseBuilderTemplate(?string $tweakwiseBuilderTemplate): LandingPageInterface;

    /**
     * @param string|null $overviewPageId
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setOverviewPageId(?string $overviewPageId): LandingPageInterface;

    /**
     * @param string|null $overviewPageImage
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setOverviewPageImage(?string $overviewPageImage): LandingPageInterface;

    /**
     * @param string|null $urlPath
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setUrlPath(?string $urlPath): LandingPageInterface;

    /**
     * @param string|null $canonicalUrl
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setCanonicalUrl(?string $canonicalUrl): LandingPageInterface;

    /**
     * @param bool $isFilterLinkAllowed
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setIsFilterLinkAllowed(bool $isFilterLinkAllowed = true): LandingPageInterface;

    /**
     * @param bool $hideSelectedFilters
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setHideSelectedFilters(bool $hideSelectedFilters = true): LandingPageInterface;
}
