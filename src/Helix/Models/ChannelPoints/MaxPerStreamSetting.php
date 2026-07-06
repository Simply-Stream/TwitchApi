<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

final readonly class MaxPerStreamSetting
{
    /**
     * @param bool $isEnabled    A Boolean value that determines whether the reward applies a limit on the number of
     *                           redemptions allowed per live stream. Is true if the reward applies a limit.
     * @param int  $maxPerStream The maximum number of redemptions allowed per live stream.
     */
    public function __construct(
        public bool $isEnabled,
        public int $maxPerStream,
    ) {
    }
}
