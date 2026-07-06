<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

final readonly class MaxPerUserPerStreamSetting
{
    /**
     * @param bool $isEnabled           A Boolean value that determines whether the reward applies a limit on the number
     *                                  of redemptions allowed per user per live stream. Is true if the reward applies a
     *                                  limit.
     * @param int  $maxPerUserPerStream The maximum number of redemptions allowed per user per live stream.
     */
    public function __construct(
        public bool $isEnabled,
        public int $maxPerUserPerStream,
    ) {
    }
}
