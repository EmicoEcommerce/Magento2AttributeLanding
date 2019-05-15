<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Block\LayeredNavigation\Navigation;


use Emico\AttributeLanding\Model\FilterHider\FilterHiderInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\LayeredNavigation\Block\Navigation;

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

class FilterHidePlugin
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
     * @param Navigation $subject
     * @param $filters
     * @return mixed
     */
    public function afterGetFilters(Navigation $subject, array $filters)
    {
        if (!$this->landingPageContext->isOnLandingPage()) {
            return $filters;
        }

        foreach ($filters as $index => $tweakwiseFilter) {
            if ($this->filterHider->shouldHideFilter($this->landingPageContext->getLandingPage(), $tweakwiseFilter)) {
                unset($filters[$index]);
            }
        }

        return $filters;
    }
}