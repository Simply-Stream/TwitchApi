<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

interface TwitchPaginatedResponseInterface
{
    public function getPagination(): ?Pagination;
}
