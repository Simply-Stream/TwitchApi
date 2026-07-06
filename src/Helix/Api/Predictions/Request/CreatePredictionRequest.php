<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Predictions\Request;

use SimplyStream\TwitchApi\Helix\Models\Predictions\CreatePrediction;

final readonly class CreatePredictionRequest
{
    /**
     * @param CreatePrediction $prediction The prediction to create.
     */
    public function __construct(
        public CreatePrediction $prediction,
    ) {}
}
