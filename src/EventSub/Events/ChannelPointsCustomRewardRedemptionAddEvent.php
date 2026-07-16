<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPointsCustomRewardRedemptionAddCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\Reward;

#[EventSubSubscription(type: 'channel.channel_points_custom_reward_redemption.add', version: '1', condition: ChannelPointsCustomRewardRedemptionAddCondition::class)]
final readonly class ChannelPointsCustomRewardRedemptionAddEvent implements EventInterface
{
    /**
     * @param string            $id                    The redemption identifier.
     * @param string            $broadcasterUserId     The requested broadcaster ID.
     * @param string            $broadcasterUserLogin  The requested broadcaster login.
     * @param string            $broadcasterUserName   The requested broadcaster display name.
     * @param string            $userId                User ID of the user that redeemed the reward.
     * @param string            $userLogin             Login of the user that redeemed the reward.
     * @param string            $userName              Display name of the user that redeemed the reward.
     * @param string            $userInput             The user input provided. Empty string if not provided.
     * @param string            $status                Defaults to unfulfilled. Possible values are unknown,
     *                                                 unfulfilled, fulfilled, and canceled.
     * @param Reward            $reward                Basic information about the reward that was redeemed, at the
     *                                                 time it was redeemed.
     * @param DateTimeInterface $redeemedAt            RFC3339 timestamp of when the reward was redeemed.
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
        public Reward $reward,
        public DateTimeInterface $redeemedAt
    ) {
    }
}
