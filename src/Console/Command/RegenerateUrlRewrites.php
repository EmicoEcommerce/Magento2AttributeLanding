<?php

declare(strict_types=1);

namespace Emico\AttributeLanding\Console\Command;

use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Emico\AttributeLanding\Model\UrlRewriteService;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegenerateUrlRewrites extends Command
{
    public function __construct(
        private LandingPageRepositoryInterface $landingPageRepository,
        private OverviewPageRepositoryInterface $overviewPageRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
        private UrlRewriteService $urlRewriteService
    ) {
        parent::__construct();
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
     * phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
     *
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
                $counter++;
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
