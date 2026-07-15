<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPointsAutomaticRewardRedemptionAddCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption\Reward;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption\RewardMessage;

#[EventSubSubscription(type: 'channel.channel_points_automatic_reward_redemption.add', version: '2', condition: ChannelPointsAutomaticRewardRedemptionAddCondition::class)]
final readonly class ChannelPointsAutomaticRewardRedemptionAddEvent implements EventInterface
{
    /**
     * @param string             $broadcasterUserId    The ID of the channel where the reward was redeemed.
     * @param string             $broadcasterUserLogin The login of the channel where the reward was redeemed.
     * @param string             $broadcasterUserName  The display name of the channel where the reward was redeemed.
     * @param string             $userId               The ID of the redeeming user.
     * @param string             $userLogin            The login of the redeeming user.
     * @param string             $userName             The display name of the redeeming user.
     * @param string             $id                   The ID of the Redemption.
     * @param Reward           $reward               An object that contains the reward information.
     * @param DateTimeInterface  $redeemedAt           The UTC date and time (in RFC3339 format) of when the reward
     *                                                 was redeemed.
     * @param RewardMessage|null $message            Optional. An object that contains the user message and emote
     *                                                 information needed to recreate the message.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $id,
        public Reward $reward,
        public DateTimeInterface $redeemedAt,
        public ?RewardMessage $message = null,
    ) {
    }
}
