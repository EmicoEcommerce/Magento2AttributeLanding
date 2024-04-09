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
    public const META_TITLE = 'meta_title';
    public const ACTIVE = 'active';
    public const HEADING = 'heading';
    public const CONTENT_FIRST = 'content_first';
    public const CONTENT_LAST = 'content_last';
    public const NAME = 'name';
    public const URL_PATH = 'url_path';
    public const META_KEYWORDS = 'meta_keywords';
    public const CATEGORY_ID = 'category_id';
    public const PAGE_ID = 'page_id';
    public const META_DESCRIPTION = 'meta_description';
    public const STORE_IDS = 'store_ids';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

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
