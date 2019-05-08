<?php
/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Model;


use Emico\AttributeLanding\Api\Data\FilterInterface;

class Filter implements FilterInterface
{
    /**
     * @var string
     */
    private $facet;

    /**
     * @var string
     */
    private $value;

    /**
     * Filter constructor.
     * @param string $facet
     * @param string $value
     */
    public function __construct(string $facet, string $value)
    {
        $this->facet = $facet;
        $this->value = $value;
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
        return $this->value;
    }
}