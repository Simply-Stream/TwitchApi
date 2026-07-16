<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Subscriptions;

final readonly class Subscription
{
    /**
     * @param string      $broadcasterId    An ID that identifies the broadcaster.
     * @param string      $broadcasterLogin The broadcaster’s login name.
     * @param string      $broadcasterName  The broadcaster’s display name.
     * @param bool        $isGift           A Boolean value that determines whether the subscription is a gift
     *                                      subscription. Is true if the subscription was gifted.
     * @param string      $tier             The type of subscription. Possible values are:
     *                                      - 1000 — Tier 1
     *                                      - 2000 — Tier 2
     *                                      - 3000 — Tier 3
     * @param string|null $gifterId         The ID of the user that gifted the subscription to the user. Is an empty
     *                                      string or null if is_gift is false.
     * @param string|null $gifterLogin      The gifter’s login name. Is an empty string or null if is_gift is false.
     * @param string|null $gifterName       The gifter’s display name. Is an empty string or null if is_gift is false.
     * @param string|null $planName         The name of the subscription.
     * @param string|null $userId           An ID that identifies the subscribing user.
     * @param string|null $userName         The user’s display name.
     * @param string|null $userLogin        The user’s login name.
     */
    public function __construct(
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
        public bool $isGift,
        public string $tier,
        public ?string $gifterId = null,
        public ?string $gifterLogin = null,
        public ?string $gifterName = null,
        public ?string $planName = null,
        public ?string $userId = null,
        public ?string $userName = null,
        public ?string $userLogin = null,
    ) {
    }
}
