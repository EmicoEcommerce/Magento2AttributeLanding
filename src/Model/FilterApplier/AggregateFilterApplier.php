<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model\FilterApplier;

use Emico\AttributeLanding\Api\Data\LandingPageInterface;
use Magento\Framework\ObjectManagerInterface;

class AggregateFilterApplier implements FilterApplierInterface
{
    /**
     * @var array|FilterApplierInterface[]
     */
    private $appliers;

    /**
     * AggregateFilterApplier constructor.
     * @param ObjectManagerInterface $objectManager
     * @param array $appliers
     */
    public function __construct(ObjectManagerInterface $objectManager, array $appliers = [])
    {
        foreach ($appliers as $applierClass) {
            $this->appliers[] = $objectManager->create($applierClass);
        }
    }

    /**
     * @param LandingPageInterface $page
     * @return mixed|void
     */
    public function applyFilters(LandingPageInterface $page)
    {
        foreach ($this->appliers as $applier) {
            if ($applier->canApplyFilters()) {
                $applier->applyFilters($page);
                return;
            }
        }
    }

    /**
     * @return bool
     */
    public function canApplyFilters(): bool
    {
        return true;
    }
}
