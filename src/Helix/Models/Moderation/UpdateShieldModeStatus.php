<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

final readonly class UpdateShieldModeStatus
{
    /**
     * @param bool $isActive A Boolean value that determines whether to activate Shield Mode. Set to true to activate
     *                       Shield Mode; otherwise, false to deactivate Shield Mode.
     */
    public function __construct(
        public bool $isActive,
    ) {}
}
