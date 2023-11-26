<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Predictions;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Outcome
{
    use SerializesModels;

    /**
     * @param string           $id              An ID that identifies this outcome.
     * @param string           $title           The outcome’s text.
     * @param string           $color           The color that visually identifies this outcome in the UX. Possible
     *                                          values are:
     *                                          - BLUE
     *                                          - PINK
     *                                          If the number of outcomes is two, the color is BLUE for the first
     *                                          outcome and PINK for the second outcome. If there are more than two
     *                                          outcomes, the color is BLUE for all outcomes.
     * @param int|null         $users           The number of unique viewers that chose this outcome.
     * @param int|null         $channelPoints   The number of Channel Points spent by viewers on this outcome.
     * @param Predictor[]|null $topPredictors   A list of viewers who were the top predictors; otherwise, null if none.
     */
    public function __construct(
        private string $id,
        private string $title,
        private string $color,
        private ?int $users = null,
        private ?int $channelPoints = null,
        private ?array $topPredictors = null
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getUsers(): ?int
    {
        return $this->users;
    }

    public function getChannelPoints(): ?int
    {
        return $this->channelPoints;
    }

    public function getTopPredictors(): ?array
    {
        return $this->topPredictors;
    }
}
