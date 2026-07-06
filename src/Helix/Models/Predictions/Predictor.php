<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Predictions;

final readonly class Predictor
{
    /**
     * @param string   $userId            An ID that identifies the viewer.
     * @param string   $userName          The viewer’s display name.
     * @param string   $userLogin         The viewer’s login name.
     * @param int|null $channelPointsUsed The number of Channel Points the viewer spent.
     * @param int|null $channelPointsWon  The number of Channel Points distributed to the viewer.
     */
    public function __construct(
        public string $userId,
        public string $userName,
        public string $userLogin,
        public ?int $channelPointsUsed = null,
        public ?int $channelPointsWon = null,
    ) {
    }
}
