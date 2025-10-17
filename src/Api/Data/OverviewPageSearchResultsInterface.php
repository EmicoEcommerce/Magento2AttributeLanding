<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface OverviewPageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Page list.
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface[]
     */
    public function getItems(): array;
}
