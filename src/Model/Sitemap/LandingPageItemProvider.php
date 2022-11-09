<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Model\Sitemap;


use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sitemap\Model\ItemProvider\CategoryConfigReader;
use Magento\Sitemap\Model\ItemProvider\ConfigReaderInterface;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;
use Magento\Sitemap\Model\SitemapItemInterface;

class LandingPageItemProvider
{
    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * @var SitemapItemInterfaceFactory
     */
    private $itemFactory;

    /**
     * @var ConfigReaderInterface
     */
    private $configReader;

    /**
     * LandingPageItemProvider constructor.
     * @param LandingPageRepositoryInterface $landingPageRepository
     * @param ObjectManager $objectManager
     */
    public function __construct(
        LandingPageRepositoryInterface $landingPageRepository,
        ObjectManagerInterface $objectManager
    ) {
        // We need to use the ObjectManager here because SitemapItemInterface and ConfigReaderInterface are only available from Magento 2.3 upwards
        // DI compile will break when executed in Magento 2.2 installation
        $this->landingPageRepository = $landingPageRepository;
        $this->itemFactory = $objectManager->get(SitemapItemInterfaceFactory::class);
        $this->configReader = $objectManager->get(CategoryConfigReader::class);
    }

    /**
     * Get sitemap items
     *
     * @param int $storeId
     * @return SitemapItemInterface[]
     */
    public function getItems($storeId)
    {
        $landingPages = $this->landingPageRepository->findAllActive();

        foreach ($landingPages as $landingPage) {

            yield $this->itemFactory->create([
                'url' => $landingPage->getUrlRewriteRequestPath(),
                'updatedAt' => $landingPage->getUpdatedAt(),
                'priority' => $this->configReader->getPriority($storeId),
                'changeFrequency' => $this->configReader->getChangeFrequency($storeId),
            ]);
        }
    }
}