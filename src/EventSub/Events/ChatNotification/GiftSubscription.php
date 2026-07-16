<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChatNotification;

final readonly class GiftSubscription
{
    /**
     * @param int         $durationMonths     The number of months the subscription is for.
     * @param string      $recipientUserId    The user ID of the subscription gift recipient.
     * @param string      $recipientUserName  The user name of the subscription gift recipient.
     * @param string      $recipientUserLogin The user login of the subscription gift recipient.
     * @param string      $subTier            The type of subscription plan being used. Possible values are:
     *                                        - 1000 — First level of paid subscription
     *                                        - 2000 — Second level of paid subscription
     *                                        - 3000 — Third level of paid subscription
     * @param int|null    $cumulativeTotal    Optional. The amount of gifts the gifter has given in this channel. Null
     *                                        if anonymous.
     * @param string|null $communityGiftId    Optional. The ID of the associated community gift. Null if not associated
     *                                        with a community gift.
     */
    public function __construct(
        public int $durationMonths,
        public string $recipientUserId,
        public string $recipientUserName,
        public string $recipientUserLogin,
        public string $subTier,
        public ?int $cumulativeTotal = null,
        public ?string $communityGiftId = null
    ) {
    }
}
