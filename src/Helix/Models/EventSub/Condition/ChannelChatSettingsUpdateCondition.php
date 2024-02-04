<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Condition;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChannelChatSettingsUpdateCondition implements ConditionInterface
{
    use SerializesModels;

    /**
     * @param string $broadcasterUserId User ID of the channel to receive chat settings update events for.
     * @param string $userId            The user ID to read chat as.
     */
    public function __construct(
        private string $broadcasterUserId,
        private string $userId,
    ) {
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
