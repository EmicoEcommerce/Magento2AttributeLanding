<?php // phpcs:ignore SlevomatCodingStandard.TypeHints.DeclareStrictTypes.DeclareStrictTypesMissing

/**
 * @author Bram Gerritsen <bgerritsen@emico.nl>
 * @copyright (c) Emico B.V. 2019
 */

namespace Emico\AttributeLanding\Api\Data;

interface FilterInterface
{
    /**
     * @return string
     */
    public function getFacet(): string;

    /**
     * @return string
     */
    public function getValue(): string;
}
