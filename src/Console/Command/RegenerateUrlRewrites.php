<?php

declare(strict_types=1);

namespace Emico\AttributeLanding\Console\Command;

use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Emico\AttributeLanding\Model\UrlRewriteService;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegenerateUrlRewrites extends Command
{
    private LandingPageRepositoryInterface $landingPageRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private UrlRewriteService $urlRewriteService;
    private OverviewPageRepositoryInterface $overviewPageRepository;

    public function __construct(
        LandingPageRepositoryInterface $landingPageRepository,
        OverviewPageRepositoryInterface $overviewPageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlRewriteService $urlRewriteService,
    ) {
        parent::__construct();
        $this->landingPageRepository = $landingPageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlRewriteService = $urlRewriteService;
        $this->overviewPageRepository = $overviewPageRepository;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('emico:attribute-landing:regenerate-rewrites')
            ->setDescription('Regenerate url rewrites for attribute landing pages.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Start regenerating urls for Attribute landing pages.</info>');

        $counter = 0;
        foreach ($this->getPages() as $page) {
            try {
                $output->writeln(
                    sprintf('Regenerating urls for %s (%s)', $page->getName(), $page->getId())
                );
                $this->urlRewriteService->generateRewrite($page);
                $counter += 1;
            } catch (Exception $e) {
                $output->writeln(
                    sprintf(
                        '<error>Couldn\'t replace url for %s (%d)' . PHP_EOL . '%s</error>',
                        $page->getName(),
                        $page->getId(),
                        $e->getMessage()
                    )
                );
            }
        }

        $output->writeln(
            sprintf(
                '<info>Finished regenerating. Regenerated %d urls.</info>',
                $counter
            )
        );

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @return iterable
     * @throws LocalizedException
     */
    protected function getPages(): iterable
    {
        $landingPages = $this->landingPageRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($landingPages->getItems() as $page) {
            yield $page;
        }

        $overviewPages = $this->overviewPageRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($overviewPages->getItems() as $page) {
            yield $page;
        }
    }
}
