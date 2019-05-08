<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\FilterHider;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\Tweakwise\Model\Catalog\Layer\Filter\Item;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

class MagentoFilterHider implements FilterHiderInterface
{
    /**
     * @param LandingPageInterface $landingPage
     * @param FilterInterface $filter
     * @param Item|null $filterItem
     * @return bool
     * @todo Make implementation
     */
    public function shouldHideFilter(LandingPageInterface $landingPage, FilterInterface $filter, Item $filterItem = null): bool
    {
        return false;
    }
}