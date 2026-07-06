<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Predictions\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Prediction;

final readonly class PredictionsResponse
{
    /** @param list<Prediction> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {}
}
