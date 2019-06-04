<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\Catalog\Layer\State;

use Emico\AttributeLanding\Controller\Page\ViewContext;
use Emico\AttributeLanding\Model\FilterHider\FilterHiderInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Catalog\Model\Layer\State;

class Plugin
{
    /**
     * @var FilterHiderInterface
     */
    private $filterHider;

    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * Plugin constructor.
     * @param LandingPageContext $landingPageContext
     * @param FilterHiderInterface $filterHider
     */
    public function __construct(LandingPageContext $landingPageContext, FilterHiderInterface $filterHider)
    {
        $this->filterHider = $filterHider;
        $this->landingPageContext = $landingPageContext;
    }

    /**
     * @param State $subject
     * @param $result
     * @return mixed
     */
    public function afterGetFilters(State $subject, $result)
    {
        if (!\is_array($result) || empty($result)) {
            return $result;
        }

        $landingPage = $this->landingPageContext->getLandingPage();
        if (!$landingPage || !$landingPage->getHideSelectedFilters()) {
            return $result;
        }

        /** @var Item $activeFilter */
        foreach ($result as $index => $activeFilter) {
            if ($this->filterHider->shouldHideFilter($landingPage, $activeFilter->getFilter(), $activeFilter)) {
                unset($result[$index]);
            }
        }

        return $result;
    }
}