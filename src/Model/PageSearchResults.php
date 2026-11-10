<?php

declare(strict_types=1);

namespace Emico\AttributeLanding\Model;

use Emico\AttributeLanding\Api\Data\PageSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

class PageSearchResults extends SearchResults implements PageSearchResultsInterface
{
}
