<?php

/**
 * @author        Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 * @noinspection  PhpFullyQualifiedNameUsageInspection
 * @noinspection  PhpPluralMixedCanBeReplacedWithArrayInspection
 * @noinspection  PhpUnnecessaryFullyQualifiedNameInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Api\Data;

use Emico\AttributeLanding\Api\UrlRewriteGeneratorInterface;

/**
 * OverviewPageInterface
 *
 * @api
 * @method mixed getData(string|array $key = '', string|int $index = null)
 * @method $this setData(string|array $key, mixed $value = null)
 */
interface OverviewPageInterface extends UrlRewriteGeneratorInterface
{
    /**
     * Field constants
     */
    public const META_TITLE       = 'meta_title';
    public const ACTIVE           = 'active';
    public const HEADING          = 'heading';
    public const CONTENT_FIRST    = 'content_first';
    public const CONTENT_LAST     = 'content_last';
    public const NAME             = 'name';
    public const URL_PATH         = 'url_path';
    public const META_KEYWORDS    = 'meta_keywords';
    public const PAGE_ID          = 'page_id';
    public const META_DESCRIPTION = 'meta_description';
    public const CREATED_AT       = 'created_at';
    public const UPDATED_AT       = 'updated_at';
    public const STORE_ID         = 'store_id';

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
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get url_path
     *
     * @return string|null
     */
    public function getUrlPath(): ?string;

    /**
     * Get heading
     *
     * @return string|null
     */
    public function getHeading(): ?string;

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
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     */
    public function setStoreId(int $storeId): OverviewPageInterface;

    /**
     * @return mixed[]
     */
    public function getOverviewPageDataForStore(): array;

    /**
     * @return mixed[]
     */
    public function getOverviewPageDataWithoutStore(): array;
}
