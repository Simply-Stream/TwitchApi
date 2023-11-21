<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;

final readonly class UpdateShieldModeStatusRequest extends AbstractModel
{
    /**
     * @param bool $isActive A Boolean value that determines whether to activate Shield Mode. Set to true to activate
     *                       Shield Mode; otherwise, false to deactivate Shield Mode.
     */
    public function __construct(
        private bool $isActive
    ) {
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
