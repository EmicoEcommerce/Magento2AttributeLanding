<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface LandingPageSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Page list.
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface[]
     */
    public function getItems();
}
