<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelChatClearCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A moderator or bot has cleared all messages from the chat room.
 */
final readonly class ChannelChatClearSubscription extends Subscription
{
    public const TYPE = 'channel.chat.clear';

    /**
     * @param array{broadcasterUserId: non-empty-string, userId: non-empty-string} $condition
     * @param Transport                                                            $transport
     * @param string|null                                                          $id
     * @param string|null                                                          $status
     * @param DateTimeInterface|null                                               $createdAt
     * @param string|null                                                          $type
     * @param string|null                                                          $version
     */
    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeInterface $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = "1"
    ) {
        parent::__construct(
            $type,
            $version,
            new ChannelChatClearCondition($condition['broadcasterUserId'], $condition['userId']),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
