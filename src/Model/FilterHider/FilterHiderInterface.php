<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

namespace Emico\AttributeLanding\Model\FilterHider;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;

/**
 * @copyright (c) Emico B.V. 2019
 */

interface FilterHiderInterface
{
    /**
     * @param LandingPageInterface $landingPage
     * @param FilterInterface $filter
     * @param Item|null $filterItem
     * @return bool
     */
    public function shouldHideFilter(
        LandingPageInterface $landingPage,
        FilterInterface $filter,
        ?Item $filterItem = null
    ): bool;
}
