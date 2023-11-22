<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class GlobalCooldownSetting
{
    use SerializesModels;

    /**
     * @param bool $isEnabled             A Boolean value that determines whether to apply a cooldown period. Is true
     *                                    if a cooldown period is enabled.
     * @param int  $globalCooldownSeconds The cooldown period, in seconds.
     */
    public function __construct(
        private bool $isEnabled,
        private int $globalCooldownSeconds,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getGlobalCooldownSeconds(): int
    {
        return $this->globalCooldownSeconds;
    }
}
