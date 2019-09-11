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
    const META_TITLE = 'meta_title';
    const CONTENT_LAST = 'content_last';
    const ACTIVE = 'active';
    const HEADING = 'heading';
    const CONTENT_FIRST = 'content_first';
    const FILTER_ATTRIBUTES = 'filter_attributes';
    const NAME = 'name';
    const URL_PATH = 'url_path';
    const META_KEYWORDS = 'meta_keywords';
    const TWEAKWISE_FILTER_TEMPLATE = 'tweakwise_filter_template';
    const TWEAKWISE_SORT_TEMPLATE = 'tweakwise_sort_template';
    const HEADER_IMAGE = 'header_image';
    const CATEGORY_ID = 'category_id';
    const PAGE_ID = 'page_id';
    const META_DESCRIPTION = 'meta_description';
    const STORE_IDS = 'store_ids';
    const OVERVIEW_PAGE_ID = 'overview_page_id';
    const OVERVIEW_PAGE_IMAGE = 'overview_page_image';
    const FILTER_LINK_ALLOWED = 'is_filter_link_allowed';
    const HIDE_SELECTED_FILTERS = 'hide_selected_filters';
    const CANONICAL_URL = 'canonical_url';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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
     * @return string|null
     */
    public function getTweakwiseFilterTemplate();

    /**
     * Get tweakwise_filter_template
     * @return string|null
     */
    public function getTweakwiseSortTemplate();

    /**
     * Get active stores IDs
     * @return int[]
     */
    public function getStoreIds(): array;

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
    public function setFilterAttributes($filterAttributes): LandingPageInterface;

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
    public function getOverviewPageId();

    /**
     * @return string
     */
    public function getOverviewPageImage();

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
    public function getCanonicalUrl();

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;
}
