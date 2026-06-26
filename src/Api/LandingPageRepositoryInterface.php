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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface LandingPageRepositoryInterface
{
    /**
     * Save Page
     *
     * @param \Emico\AttributeLanding\Api\Data\LandingPageInterface $page
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     * @throws LocalizedException
     */
    public function save(LandingPageInterface $page): LandingPageInterface;

    /**
     * Retrieve Page
     *
     * @param int $pageId
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getById(int $pageId): LandingPageInterface;

    /**
     * Retrieve Page
     *
     * @param int $pageId
     * @param int $storeId
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getByIdWithStore(int $pageId, int $storeId): LandingPageInterface;

    /**
     * Retrieve Page matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Emico\AttributeLanding\Api\Data\PageSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): PageSearchResultsInterface;

    /**
     * Delete Page
     *
     * @param \Emico\AttributeLanding\Api\Data\LandingPageInterface $page
     *
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(LandingPageInterface $page): bool;

    /**
     * Delete Page by ID
     *
     * @param int $pageId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $pageId): bool;

    /**
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface[]
     */
    public function findAllActive(): array;

    /**
     * @param \Emico\AttributeLanding\Api\Data\OverviewPageInterface $overviewPage
     *
     * @return \Emico\AttributeLanding\Api\Data\LandingPageInterface[]
     */
    public function findAllByOverviewPage(OverviewPageInterface $overviewPage): array;
}
