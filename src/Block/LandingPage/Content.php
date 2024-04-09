<?php

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Block\LandingPage;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\View\Element\Template;
use Psr\Log\LoggerInterface;

class Content extends Template
{
    /**
     * @var LandingPageContext
     */
    private $landingPageContext;

    /**
     * @var \Magento\Framework\Filter\Template
     */
    private $pageFilter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PageContent constructor.
     * @param Template\Context $context
     * @param LandingPageContext $landingPageContext
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        Template\Context $context,
        LandingPageContext $landingPageContext,
        FilterProvider $filterProvider,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->landingPageContext = $landingPageContext;
        $this->pageFilter = $filterProvider->getPageFilter();
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getTopContent(): string
    {
        return $this->getFilteredContent($this->getLandingPage()->getContentFirst() ?? '');
    }

    /**
     * @return string
     */
    public function getBottomContent(): string
    {
        return $this->getFilteredContent($this->getLandingPage()->getContentLast() ?? '');
    }

    /**
     * @return LandingPageInterface
     */
    protected function getLandingPage(): LandingPageInterface
    {
        return $this->landingPageContext->getLandingPage();
    }

    /**
     * @param string $content
     * @return string
     */
    protected function getFilteredContent(string $content): string
    {
        try {
            return $this->pageFilter->filter($content);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            return '';
        }
    }
}
