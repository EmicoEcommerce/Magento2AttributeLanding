<?php

/**
 * @author        Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection  PhpUnnecessaryFullyQualifiedNameInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Page list.
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface[]
     */
    public function getItems();
}
