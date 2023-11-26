<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\CharityDonationCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * Sends an event notification when a user donates to the broadcaster’s charity campaign.
 */
final readonly class CharityDonationSubscription extends Subscription
{
    public const TYPE = 'channel.charity_campaign.donate';

    public function __construct(
        array $condition,
        Transport $transport,
        ?string $id = null,
        ?string $status = null,
        ?DateTimeImmutable $createdAt = null,
        ?string $type = self::TYPE,
        ?string $version = "1"
    ) {
        parent::__construct(
            $type,
            $version,
            new CharityDonationCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
