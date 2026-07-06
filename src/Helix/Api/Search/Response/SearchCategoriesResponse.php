<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Search\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Search\Category;

final readonly class SearchCategoriesResponse
{
    /** @param list<Category> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {}
}
