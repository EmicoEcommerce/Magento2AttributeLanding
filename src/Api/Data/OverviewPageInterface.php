<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Api\Data;

interface OverviewPageInterface
{
    /**
     * Field constants
     */
    const META_TITLE = 'meta_title';
    const ACTIVE = 'active';
    const HEADING = 'heading';
    const CONTENT_FIRST = 'content_first';
    const CONTENT_LAST = 'content_last';
    const NAME = 'name';
    const URL_PATH = 'url_path';
    const META_KEYWORDS = 'meta_keywords';
    const CATEGORY_ID = 'category_id';
    const PAGE_ID = 'page_id';
    const META_DESCRIPTION = 'meta_description';
    const STORE_IDS = 'store_ids';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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
    public function getName();

    /**
     * Get url_path
     * @return string|null
     */
    public function getUrlPath();

    /**
     * Get heading
     * @return string|null
     */
    public function getHeading();

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
     * Get active stores IDs
     * @return int[]
     */
    public function getStoreIds(): array;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @return string
     */
    public function getUpdatedAt(): string;
}