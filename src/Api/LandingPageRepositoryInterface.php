<?php


namespace Emico\AttributeLanding\Api;


use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface LandingPageRepositoryInterface
{
    /**
     * Save Page
     * @param \Emico\AttributeLanding\Api\Data\LandingPageInterface $page
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(LandingPageInterface $page): LandingPageInterface;

    /**
     * Retrieve Page
     * @param int $pageId
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws NoSuchEntityException
     */
    public function getById(int $pageId): LandingPageInterface;

    /**
     * Retrieve Page matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Emico\AttributeLanding\Api\Data\PageSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Page
     * @param \Emico\AttributeLanding\Api\Data\LandingPageInterface $page
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(LandingPageInterface $page): bool;

    /**
     * Delete Page by ID
     * @param int $pageId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $pageId): bool;

    /**
     * @return LandingPageInterface[]
     */
    public function findAllActive(): array;

    /**
     * @param OverviewPageInterface $overviewPage
     * @return LandingPageInterface[]
     */
    public function findAllByOverviewPage(OverviewPageInterface $overviewPage): array;
}
