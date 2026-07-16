<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

final readonly class Reward
{
    /**
     * @param string $id     The ID that uniquely identifies the redeemed reward.
     * @param string $title  The reward’s title.
     * @param string $prompt The prompt displayed to the viewer if user input is required.
     * @param int    $cost   The reward’s cost, in Channel Points.
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $prompt,
        public int $cost,
    ) {
    }
}
