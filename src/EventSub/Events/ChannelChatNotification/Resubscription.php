<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotification;

final readonly class Resubscription
{
    /**
     * @param int         $cumulativeMonths  The total number of months the user has subscribed.
     * @param int         $durationMonths    The number of months the subscription is for.
     * @param string      $subTier           The type of subscription plan being used. Possible values are:
     *                                       - 1000 — First level of paid or Prime subscription
     *                                       - 2000 — Second level of paid subscription
     *                                       - 3000 — Third level of paid subscription
     * @param bool        $isPrime           Indicates if the resub was obtained through Amazon Prime.
     * @param bool        $isGift            Whether or not the resub was a result of a gift.
     * @param int|null    $streakMonths      Optional. The number of consecutive months the user has subscribed.
     * @param bool|null   $gifterIsAnonymous Optional. Whether or not the gift was anonymous.
     * @param string|null $gifterUserId      Optional. The user ID of the subscription gifter. Null if anonymous.
     * @param string|null $gifterUserName    Optional. The user name of the subscription gifter. Null if anonymous.
     * @param string|null $gifterUserLogin   Optional. The user login of the subscription gifter. Null if anonymous.
     */
    public function __construct(
        public int $cumulativeMonths,
        public int $durationMonths,
        public string $subTier,
        public bool $isPrime,
        public bool $isGift,
        public ?int $streakMonths = null,
        public ?bool $gifterIsAnonymous = null,
        public ?string $gifterUserId = null,
        public ?string $gifterUserName = null,
        public ?string $gifterUserLogin = null
    ) {
    }
}
