<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Predictions;

final readonly class Outcome
{
    /**
     * @param string             $id            An ID that identifies this outcome.
     * @param string             $title         The outcome’s text.
     * @param string             $color         The color that visually identifies this outcome in the UX. Possible
     *                                          values are:
     *                                          - BLUE
     *                                          - PINK
     *                                          If the number of outcomes is two, the color is BLUE for the first
     *                                          outcome and PINK for the second outcome. If there are more than two
     *                                          outcomes, the color is BLUE for all outcomes.
     * @param int|null           $users         The number of unique viewers that chose this outcome.
     * @param int|null           $channelPoints The number of Channel Points spent by viewers on this outcome.
     * @param list<Predictor>|null $topPredictors A list of viewers who were the top predictors; otherwise, null if
     *                                          none.
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $color,
        public ?int $users = null,
        public ?int $channelPoints = null,
        public ?array $topPredictors = null,
    ) {
    }
}
