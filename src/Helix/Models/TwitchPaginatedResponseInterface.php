<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models;

interface TwitchPaginatedResponseInterface
{
    public function getPagination(): ?Pagination;
}
