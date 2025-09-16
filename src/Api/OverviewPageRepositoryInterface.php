<?php

namespace Emico\AttributeLanding\Api;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface OverviewPageRepositoryInterface
{
    /**
     * Save Page
     * @param \Emico\AttributeLanding\Api\Data\OverviewPageInterface $page
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OverviewPageInterface $page): OverviewPageInterface;

    /**
     * Retrieve Page
     * @param int $pageId
     * @param int $storeId
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws NoSuchEntityException
     */
    public function getByIdWithStore(int $pageId, int $storeId): OverviewPageInterface;

    /**
     * Retrieve Page matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Emico\AttributeLanding\Api\Data\PageSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Page
     * @param \Emico\AttributeLanding\Api\Data\OverviewPageInterface $page
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(OverviewPageInterface $page): bool;

    /**
     * Delete Page by ID
     * @param int $pageId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $pageId): bool;

    /**
     * @return OverviewPageInterface[]
     */
    public function findAllActive(): array;

    /**
     * @param LandingPageInterface $landingPage
     * @return OverviewPageInterface
     * @throws NoSuchEntityException
     */
    public function getByLandingPage(LandingPageInterface $landingPage): OverviewPageInterface;
}
