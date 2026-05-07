<?php

/**
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */

declare(strict_types=1);

namespace Emico\AttributeLanding\Api;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\Data\PageSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface OverviewPageRepositoryInterface
{
    /**
     * Save Page
     *
     * @param \Emico\AttributeLanding\Api\Data\OverviewPageInterface $page
     *
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(OverviewPageInterface $page): OverviewPageInterface;

    /**
     * Retrieve Page
     *
     * @param int $pageId
     * @param int $storeId
     *
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws NoSuchEntityException
     */
    public function getByIdWithStore(int $pageId, int $storeId): OverviewPageInterface;

    /**
     * @param int $pageId
     *
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $pageId): OverviewPageInterface;

    /**
     * Retrieve Page matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Emico\AttributeLanding\Api\Data\PageSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): PageSearchResultsInterface;

    /**
     * Delete Page
     *
     * @param \Emico\AttributeLanding\Api\Data\OverviewPageInterface $page
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(OverviewPageInterface $page): bool;

    /**
     * Delete Page by ID
     *
     * @param int $pageId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $pageId): bool;

    /**
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface[]
     */
    public function findAllActive(): array;

    /**
     * @param \Emico\AttributeLanding\Api\Data\LandingPageInterface $landingPage
     *
     * @return \Emico\AttributeLanding\Api\Data\OverviewPageInterface
     * @throws NoSuchEntityException
     */
    public function getByLandingPage(LandingPageInterface $landingPage): OverviewPageInterface;
}
