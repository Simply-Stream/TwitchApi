<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelChatSettingsUpdateCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

final readonly class ChannelChatSettingsUpdateSubscription extends Subscription
{
    public const TYPE = 'channel.chat_settings.update';

    /**
     * @param array{broadcasterUserId: string, userId: string} $condition
     * @param Transport                                        $transport
     * @param string|null                                      $id
     * @param string|null                                      $status
     * @param DateTimeImmutable|null                           $createdAt
     * @param string|null                                      $type
     * @param string|null                                      $version
     */
    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeImmutable $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = 'beta'
    ) {
        parent::__construct(
            $type,
            $version,
            new ChannelChatSettingsUpdateCondition(
                $condition['broadcasterUserId'],
                $condition['userId']
            ),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
