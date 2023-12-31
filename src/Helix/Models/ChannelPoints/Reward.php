<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Reward
{
    use SerializesModels;

    /**
     * @param string $id     The ID that uniquely identifies the redeemed reward.
     * @param string $title  The reward’s title.
     * @param string $prompt The prompt displayed to the viewer if user input is required.
     * @param int    $cost   The reward’s cost, in Channel Points.
     */
    public function __construct(
        private string $id,
        private string $title,
        private string $prompt,
        private int $cost
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

    public function getPrompt(): string
    {
        return $this->prompt;
    }

    public function getCost(): int
    {
        return $this->cost;
    }
}
