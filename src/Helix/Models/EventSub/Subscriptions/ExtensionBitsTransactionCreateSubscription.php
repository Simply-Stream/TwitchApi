<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ExtensionBitsTransactionCreateCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

/**
 * A Bits transaction occurred for a specified Twitch Extension.
 */
final readonly class ExtensionBitsTransactionCreateSubscription extends Subscription
{
    public const TYPE = 'extension.bits_transaction.create';

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
            new ExtensionBitsTransactionCreateCondition(...$condition),
            $transport,
            $id,
            $status,
            $createdAt
        );
    }
}
