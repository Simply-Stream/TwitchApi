<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Predictions\Response;

use SimplyStream\TwitchApi\Helix\Models\Predictions\Prediction;

final readonly class PredictionResponse
{
    /** @param list<Prediction> $data */
    public function __construct(
        public array $data,
    ) {
    }
}
