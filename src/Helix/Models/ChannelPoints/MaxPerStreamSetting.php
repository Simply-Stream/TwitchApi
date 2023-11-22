<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class MaxPerStreamSetting
{
    use SerializesModels;

    /**
     * @param bool $isEnabled    A Boolean value that determines whether the reward applies a limit on the number of
     *                           redemptions allowed per live stream. Is true if the reward applies a limit.
     * @param int  $maxPerStream The maximum number of redemptions allowed per live stream.
     */
    public function __construct(
        private bool $isEnabled,
        private int $maxPerStream,
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getMaxPerStream(): int
    {
        return $this->maxPerStream;
    }
}
