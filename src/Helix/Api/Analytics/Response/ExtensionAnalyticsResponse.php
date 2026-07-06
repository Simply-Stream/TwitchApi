<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Analytics\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Analytics\ExtensionAnalytics;

final readonly class ExtensionAnalyticsResponse
{
    /** @param list<ExtensionAnalytics> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}
