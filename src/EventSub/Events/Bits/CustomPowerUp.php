<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Bits;

final readonly class CustomPowerUp
{
    /**
     * @param string $title    The title of the custom Power-up.
     * @param string $rewardId The ID of the custom Power-up.
     */
    public function __construct(
        public string $title,
        public string $rewardId,
    ) {
    }
}
