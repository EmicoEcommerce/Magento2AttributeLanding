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
     * @param string   $facet
     * @param string[] $values
     */
    public function __construct(private readonly string $facet, private readonly array $values)
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
     * @return string
     */
    public function getValue(): string
    {
        return $this->values[0] ?? '';
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
