<?php

/**
 * @author        Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Block\LandingPage;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Model\LandingPageContext;
use Exception;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Filter\Template as FilterTemplate;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Psr\Log\LoggerInterface;

class Content extends Template implements IdentityInterface
{
    /**
     * @var FilterTemplate
     */
    private FilterTemplate $pageFilter;

    /**
     * PageContent constructor.
     *
     * @param Context            $context
     * @param LandingPageContext $landingPageContext
     * @param FilterProvider     $filterProvider
     * @param LoggerInterface    $logger
     */
    public function __construct(
        Context $context,
        private readonly LandingPageContext $landingPageContext,
        FilterProvider $filterProvider,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct($context);
        $this->pageFilter = $filterProvider->getPageFilter();
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
    public function getLandingPage(): LandingPageInterface
    {
        return $this->landingPageContext->getLandingPage();
    }

    /**
     * @return array|string[]
     */
    public function getIdentities(): array
    {
        /** @phpstan-ignore-next-line */
        return $this->getLandingPage()->getIdentities();
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function getFilteredContent(string $content): string
    {
        try {
            return $this->pageFilter->filter($content);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());

            return '';
        }
    }
}
