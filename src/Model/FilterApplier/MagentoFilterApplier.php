<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\FilterApplier;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

class MagentoFilterApplier implements FilterApplierInterface
{
    /**
     * @param LandingPageInterface $page
     * @return mixed|void
     */
    public function applyFilters(LandingPageInterface $page)
    {
        //@todo Magento native implementation for applying filters
    }

    /**
     * @return bool
     */
    public function canApplyFilters(): bool
    {
        return false;
    }
}