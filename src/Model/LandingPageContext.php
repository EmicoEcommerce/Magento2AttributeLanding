<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Model;


use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;

class LandingPageContext
{
    /**
     * @var LandingPageInterface
     */
    protected $landingPage;

    /**
     * @var OverviewPageInterface
     */
    protected $overviewPage;

    /**
     * @return LandingPageInterface
     */
    public function getLandingPage()
    {
        return $this->landingPage;
    }

    /**
     * @param LandingPageInterface $page
     */
    public function setLandingPage(LandingPageInterface $page)
    {
        $this->landingPage = $page;
    }

    /**
     * @return bool
     */
    public function isOnLandingPage(): bool
    {
        return $this->landingPage !== null;
    }

    /**
     * @return OverviewPageInterface
     */
    public function getOverviewPage()
    {
        return $this->overviewPage;
    }

    /**
     * @param OverviewPageInterface $overviewPage
     */
    public function setOverviewPage(OverviewPageInterface $overviewPage)
    {
        $this->overviewPage = $overviewPage;
    }

    /**
     * @return bool
     */
    public function isOnOverviewPage(): bool
    {
        return $this->overviewPage !== null;
    }
}