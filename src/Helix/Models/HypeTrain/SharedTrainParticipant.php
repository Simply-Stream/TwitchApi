<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\HypeTrain;

final readonly class SharedTrainParticipant
{
    /**
     * @param string $broadcasterUserId    The ID of the broadcaster participating in the shared Hype Train.
     * @param string $broadcasterUserLogin The login of the broadcaster participating in the shared Hype Train.
     * @param string $broadcasterUserName  The display name of the broadcaster participating in the shared Hype
     *                                    Train.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
    ) {
    }
}
