<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Predictions\Request;

use SimplyStream\TwitchApi\Helix\Models\Predictions\EndPrediction;

final readonly class EndPredictionRequest
{
    /**
     * @param EndPrediction $prediction The prediction to lock, resolve or cancel.
     */
    public function __construct(
        public EndPrediction $prediction,
    ) {
    }
}
