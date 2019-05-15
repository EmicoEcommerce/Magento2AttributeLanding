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
    public const HEADER_IMAGE = 'header_image';
    public const CATEGORY_ID = 'category_id';
    public const PAGE_ID = 'page_id';
    public const META_DESCRIPTION = 'meta_description';
    public const STORE_IDS = 'store_ids';
    public const OVERVIEW_PAGE_ID = 'overview_page_id';
    public const OVERVIEW_PAGE_IMAGE = 'overview_page_image';
    public const FILTER_LINK_ALLOWED = 'is_filter_link_allowed';
    public const HIDE_SELECTED_FILTERS = 'hide_selected_filters';
    public const CANONICAL_URL = 'canonical_url';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

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
    public function getExtensionAttributes(): ?LandingPageExtensionInterface;

    /**
     * Get page_id
     * @return int|null
     */
    public function getPageId(): ?int;

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
    public function getUrlPath(): ?string;

    /**
     * Get category_id
     * @return int|null
     */
    public function getCategoryId(): ?int;

    /**
     * Get heading
     * @return string|null
     */
    public function getHeading(): ?string;

    /**
     * Get header_image
     * @return string|null
     */
    public function getHeaderImage(): ?string;

    /**
     * Get meta_title
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * Get meta_keywords
     * @return string|null
     */
    public function getMetaKeywords(): ?string;

    /**
     * Get meta_description
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * Get content_first
     * @return string|null
     */
    public function getContentFirst(): ?string;

    /**
     * Get content_last
     * @return string|null
     */
    public function getContentLast(): ?string;

    /**
     * Get tweakwise_filter_template
     * @return string|null
     */
    public function getTweakwiseFilterTemplate(): ?int;

    /**
     * Get active stores IDs
     * @return int[]
     */
    public function getStoreIds(): array;

    /**
     * Get filter_attributes
     * @return string|null
     */
    public function getFilterAttributes(): ?string;

    /**
     * Set filter_attributes
     * @param string $filterAttributes
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     */
    public function setFilterAttributes($filterAttributes): LandingPageInterface;

    /**
     * @return \Emico\AttributeLanding\Api\Data\FilterInterface[]
     */
    public function getFilters(): array;

    /**
     * @return array
     */
    public function getUnserializedFilterAttributes(): array;

    /**
     * @return int
     */
    public function getOverviewPageId(): ?int;

    /**
     * @return string
     */
    public function getOverviewPageImage(): ?string;

    /**
     * @return bool
     */
    public function getIsFilterLinkAllowed(): bool;

    /**
     * @return bool
     */
    public function getHideSelectedFilters(): bool;

    /**
     * @return string
     */
    public function getCanonicalUrl(): ?string;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;
}