<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

final readonly class GlobalCooldownSetting
{
    /**
     * @param bool $isEnabled             A Boolean value that determines whether to apply a cooldown period. Is true if
     *                                    a cooldown period is enabled.
     * @param int  $globalCooldownSeconds The cooldown period, in seconds.
     */
    public function __construct(
        public bool $isEnabled,
        public int $globalCooldownSeconds,
    ) {
    }
}
