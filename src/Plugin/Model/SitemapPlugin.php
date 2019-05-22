<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Plugin\Model;

use Emico\AttributeLanding\Api\LandingPageRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\Sitemap\Helper\Data;
use Magento\Sitemap\Model\Sitemap;

class SitemapPlugin
{
    /**
     * @var LandingPageRepositoryInterface
     */
    private $landingPageRepository;

    /**
     * @var Data
     */
    private $helper;

    public function __construct(
        Data $helper,
        LandingPageRepositoryInterface $landingPageRepository
    ) {
        $this->helper = $helper;
        $this->landingPageRepository = $landingPageRepository;
    }

    public function afterCollectSitemapItems(Sitemap $subject)
    {
        $storeId = $subject->getStoreId();
        $sitemapPages = [];
        foreach ($this->landingPageRepository->findAllActive() as $landingPage) {
            $id = 'landingpage' . $landingPage->getPageId();
            $page = new DataObject([
                'id' => $id,
                'url' => $landingPage->getUrlPath(),
                'updated_at' => $landingPage->getUpdatedAt()
            ]);

            $sitemapPages[$id] = $page;
        }

        $subject->addSitemapItem(new DataObject([
            'changefreq' => $this->helper->getCategoryChangefreq($storeId),
            'priority' => $this->helper->getCategoryPriority($storeId),
            'collection' => $sitemapPages,
        ]));
    }
}