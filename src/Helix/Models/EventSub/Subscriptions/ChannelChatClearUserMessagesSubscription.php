<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelChatClearUserMessagesCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * (BETA) A moderator or bot has cleared all messages from a specific user.
 */
final readonly class ChannelChatClearUserMessagesSubscription extends Subscription
{
    public const TYPE = 'channel.chat.clear_user_messages';

    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeImmutable $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = "beta"
    ) {
        parent::__construct(
            $type,
            $version,
            new ChannelChatClearUserMessagesCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
