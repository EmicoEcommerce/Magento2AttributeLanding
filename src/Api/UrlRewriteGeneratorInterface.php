<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Api;


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
     * Get active stores IDs
     * @return int[]
     */
    public function getStoreIds(): array;
}