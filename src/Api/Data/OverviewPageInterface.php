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
     * Set active
     *
     * @param bool $active
     *
     * @return static
     */
    public function setActive(bool $active): static;

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set name
     *
     * @param string|null $name
     *
     * @return static
     */
    public function setName(?string $name): static;

    /**
     * Get url_path
     *
     * @return string|null
     */
    public function getUrlPath(): ?string;

    /**
     * Set url_path
     *
     * @param string|null $urlPath
     *
     * @return static
     */
    public function setUrlPath(?string $urlPath): static;

    /**
     * Get heading
     *
     * @return string|null
     */
    public function getHeading(): ?string;

    /**
     * Set heading
     *
     * @param string|null $heading
     *
     * @return static
     */
    public function setHeading(?string $heading): static;

    /**
     * Get meta_title
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * Set meta_title
     *
     * @param string|null $metaTitle
     *
     * @return static
     */
    public function setMetaTitle(?string $metaTitle): static;

    /**
     * Get meta_keywords
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string;

    /**
     * Set meta_keywords
     *
     * @param string|null $metaKeywords
     *
     * @return static
     */
    public function setMetaKeywords(?string $metaKeywords): static;

    /**
     * Get meta_description
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * Set meta_description
     *
     * @param string|null $metaDescription
     *
     * @return static
     */
    public function setMetaDescription(?string $metaDescription): static;

    /**
     * Get content_first
     *
     * @return string|null
     */
    public function getContentFirst(): ?string;

    /**
     * Set content_first
     *
     * @param string|null $contentFirst
     *
     * @return static
     */
    public function setContentFirst(?string $contentFirst): static;

    /**
     * Get content_last
     *
     * @return string|null
     */
    public function getContentLast(): ?string;

    /**
     * Set content_last
     *
     * @param string|null $contentLast
     *
     * @return static
     */
    public function setContentLast(?string $contentLast): static;

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
