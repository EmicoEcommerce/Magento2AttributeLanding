<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\FilterInterface;

class Filter implements FilterInterface
{
    /**
     * Filter constructor.
     *
     * @param string $facet
     * @param array  $value
     */
    public function __construct(private readonly string $facet, private readonly array $value)
    {
    }

    /**
     * @return string
     */
    public function getFacet(): string
    {
        return $this->facet;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }
}
