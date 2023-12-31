<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Subscriptions;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Subscription
{
    use SerializesModels;

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
        private string $broadcasterId,
        private string $broadcasterLogin,
        private string $broadcasterName,
        private bool $isGift,
        private string $tier,
        private ?string $gifterId = null,
        private ?string $gifterLogin = null,
        private ?string $gifterName = null,
        private ?string $planName = null,
        private ?string $userId = null,
        private ?string $userName = null,
        private ?string $userLogin = null,
    ) {
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getBroadcasterLogin(): string
    {
        return $this->broadcasterLogin;
    }

    public function getBroadcasterName(): string
    {
        return $this->broadcasterName;
    }

    public function isGift(): bool
    {
        return $this->isGift;
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function getGifterId(): ?string
    {
        return $this->gifterId;
    }

    public function getGifterLogin(): ?string
    {
        return $this->gifterLogin;
    }

    public function getGifterName(): ?string
    {
        return $this->gifterName;
    }

    public function getPlanName(): ?string
    {
        return $this->planName;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function getUserLogin(): ?string
    {
        return $this->userLogin;
    }
}
