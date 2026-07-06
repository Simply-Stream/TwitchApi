<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateShieldModeStatus;

final readonly class UpdateShieldModeStatusRequest
{
    /**
     * @param string                 $broadcasterId The ID of the broadcaster whose Shield Mode you want to activate or
     *                                              deactivate.
     * @param string                 $moderatorId   The ID of the broadcaster or a user that is one of the broadcaster’s
     *                                              moderators. This ID must match the user ID in the access token.
     * @param UpdateShieldModeStatus $status        The Shield Mode status to set.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public UpdateShieldModeStatus $status,
    ) {}
}
