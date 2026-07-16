<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub;

use DateTimeInterface;

final readonly class EventSubSubscription
{
    /**
     * @param string               $id        An ID that identifies the subscription.
     * @param SubscriptionStatus   $status    The subscription's status.
     * @param string               $type      The subscription's type.
     * @param string               $version   The version number that identifies this definition of the subscription's
     *                                        data.
     * @param array<string, mixed> $condition The subscription's parameter values.
     * @param Transport            $transport The transport details used to send the notifications.
     * @param DateTimeInterface    $createdAt The date and time (in RFC3339 format) of when the subscription was
     *                                        created.
     * @param int                  $cost      The amount that the subscription counts against your limit.
     */
    public function __construct(
        public string $id,
        public SubscriptionStatus $status,
        public string $type,
        public string $version,
        public array $condition,
        public Transport $transport,
        public DateTimeInterface $createdAt,
        public int $cost,
    ) {
    }
}
