<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Predictions;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class CreatePredictionRequest extends AbstractModel
{
    /**
     * @param string                     $broadcasterId    The ID of the broadcaster that’s running the prediction. This ID
     *                                                     must match the user ID in the user access token.
     * @param string                     $title            The question that the broadcaster is asking. For example, Will I
     *                                                     finish this entire pizza? The title is limited to a maximum of 45
     *                                                     characters.
     * @param array{array{title:string}} $outcomes         The list of possible outcomes that the viewers may choose from.
     *                                                     The list must contain a minimum of 2 choices and up to a maximum
     *                                                     of 10 choices.
     * @param int                        $predictionWindow The length of time (in seconds) that the prediction will run for.
     *                                                     The minimum is 30 seconds and the maximum is 1800 seconds (30
     *                                                     minutes).
     */
    public function __construct(
        private string $broadcasterId,
        private string $title,
        private array $outcomes,
        private int $predictionWindow
    ) {
        Assert::stringNotEmpty($this->broadcasterId, 'Broadcaster ID can\'t be empty');
        Assert::stringNotEmpty($this->title, 'Title can\'t be empty');
        Assert::maxLength($this->title, 45, 'Title can\'t be longer than 45 characters');
        Assert::greaterThanEq($this->predictionWindow, 30, 'Prediction window needs to be at least 30 seconds');
        Assert::lessThanEq($this->predictionWindow, 1800, 'Prediction window can\'t be longer than 1800 seconds');
        Assert::countBetween($this->outcomes, 2, 10, 'Prediction outcomes may only be between 2 to 10 outcomes');
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getOutcomes(): array
    {
        return $this->outcomes;
    }

    public function getPredictionWindow(): int
    {
        return $this->predictionWindow;
    }
}
