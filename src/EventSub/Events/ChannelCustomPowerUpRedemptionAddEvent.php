<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelCustomPowerUpRedemptionAddCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\ChannelCustomPowerUpRedemption\CustomPowerUp;

#[EventSubSubscription(type: 'channel.custom_power_up_redemption.add', version: '1', condition: ChannelCustomPowerUpRedemptionAddCondition::class)]
final readonly class ChannelCustomPowerUpRedemptionAddEvent implements EventInterface
{
    /**
     * @param string            $id                   The redemption identifier.
     * @param string            $broadcasterUserId    The requested broadcaster ID.
     * @param string            $broadcasterUserLogin The requested broadcaster login.
     * @param string            $broadcasterUserName  The requested broadcaster display name.
     * @param string            $userId               User ID of the user that redeemed the custom Power-up.
     * @param string            $userLogin            Login of the user that redeemed the custom Power-up.
     * @param string            $userName             Display name of the user that redeemed the custom Power-up.
     * @param string            $userInput            The user input provided. Empty string if not provided.
     * @param string            $status               Defaults to unfulfilled. Possible values are: unknown,
     *                                                unfulfilled, fulfilled, canceled.
     * @param CustomPowerUp     $customPowerUp        Basic information about the custom Power-up that was redeemed, at
     *                                                the time it was redeemed.
     * @param DateTimeInterface $redeemedAt           RFC3339 timestamp of when the custom Power-up was redeemed.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $userInput,
        public string $status,
        public CustomPowerUp $customPowerUp,
        public DateTimeInterface $redeemedAt,
    ) {
    }
}
