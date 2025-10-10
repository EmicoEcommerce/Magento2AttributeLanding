<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2017
 */

namespace Emico\AttributeLanding\Model\Config\Source;

use Emico\AttributeLanding\Api\Data\OverviewPageInterface;
use Emico\AttributeLanding\Api\OverviewPageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Option\ArrayInterface;

class OverviewPage implements ArrayInterface
{
    /**
     * @var OverviewPageRepositoryInterface
     */
    private $overviewPageRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * OverviewPage constructor.
     * @param OverviewPageRepositoryInterface $overviewPageRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        OverviewPageRepositoryInterface $overviewPageRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->overviewPageRepository = $overviewPageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        $criteria = $this->searchCriteriaBuilder
            ->addSortOrder(new SortOrder(['field' => OverviewPageInterface::URL_PATH]))
            ->create();

        $options = [
            ['value' => null, 'label' => __('Select a page')],
        ];
        foreach ($this->overviewPageRepository->getList($criteria)->getItems() as $overviewPage) {
            $options[] = [
                'value' => $overviewPage->getPageId(),
                'label' => $overviewPage->getName()
            ];
        }

        return $options;
    }
}
