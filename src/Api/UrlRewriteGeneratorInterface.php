<?php

/**
 * @author        Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Api;

/**
 * UrlRewriteGeneratorInterface
 *
 * @api
 * @method int getStoreId()
 * @method int[] getStoreIds()
 */
interface UrlRewriteGeneratorInterface
{
    /**
     * @return string
     */
    public function getUrlRewriteEntityType(): string;

    /**
     * @return int
     */
    public function getUrlRewriteEntityId(): int;

    /**
     * @return string
     */
    public function getUrlRewriteTargetPath(): string;

    /**
     * @return string
     */
    public function getUrlRewriteRequestPath(): string;

    /**
     * Get page_id
     *
     * @return int
     */
    public function getPageId(): int;

    /**
     * Set page_id
     *
     * @param int $pageId
     *
     * @return static
     */
    public function setPageId(int $pageId): static;
}
