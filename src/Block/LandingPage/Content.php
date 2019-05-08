<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Block\LandingPage;


use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Framework\View\Element\Template;

class Content extends Template
{
    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * PageContent constructor.
     * @param Template\Context $context
     * @param LandingPageContext $landingPageContext
     */
    public function __construct(Template\Context $context, LandingPageContext $landingPageContext)
    {
        parent::__construct($context);
        $this->landingPageContext = $landingPageContext;
    }

    /**
     * @return string
     */
    public function getTopContent(): string
    {
        return $this->getLandingPage()->getContentFirst() ?? '';
    }

    /**
     * @return string
     */
    public function getBottomContent(): string
    {
        return $this->getLandingPage()->getContentLast() ?? '';
    }

    /**
     * @return LandingPageInterface
     */
    protected function getLandingPage(): LandingPageInterface
    {
        return $this->landingPageContext->getLandingPage();
    }
}