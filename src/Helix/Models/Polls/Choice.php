<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Polls;

final readonly class Choice
{
    /**
     * @param string   $id                 An ID that identifies this choice.
     * @param string   $title              The choice’s title. The title may contain a maximum of 25 characters.
     * @param int|null $votes              The total number of votes cast for this choice.
     * @param int|null $channelPointsVotes The number of votes cast using Channel Points.
     * @param int|null $bitsVotes          Not used; will be set to 0.
     */
    public function __construct(
        public string $id,
        public string $title,
        public ?int $votes = null,
        public ?int $channelPointsVotes = null,
        public ?int $bitsVotes = null,
    ) {
    }
}
