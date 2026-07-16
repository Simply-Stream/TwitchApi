<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPointsAutomaticRewardRedemptionAddCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption\RewardV1;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsAutomaticRewardRedemption\RewardMessageV1;

#[EventSubSubscription(type: 'channel.channel_points_automatic_reward_redemption.add', version: '1', condition: ChannelPointsAutomaticRewardRedemptionAddCondition::class)]
final readonly class ChannelPointsAutomaticRewardRedemptionAddV1Event implements EventInterface
{
    /**
     * @param string            $broadcasterUserId    The ID of the channel where the reward was redeemed.
     * @param string            $broadcasterUserLogin The login of the channel where the reward was redeemed.
     * @param string            $broadcasterUserName  The display name of the channel where the reward was redeemed.
     * @param string            $userId               The ID of the redeeming user.
     * @param string            $userLogin            The login of the redeeming user.
     * @param string            $userName             The display name of the redeeming user.
     * @param string            $id                   The ID of the Redemption.
     * @param RewardV1          $reward               An object that contains the reward information.
     * @param RewardMessageV1   $message              An object that contains the user message and emote information
     *                                                needed to recreate the message.
     * @param DateTimeInterface $redeemedAt           The UTC date and time (in RFC3339 format) of when the reward was
     *                                                redeemed.
     * @param string|null       $userInput            Optional. A string that the user entered if the reward requires
     *                                                input.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $id,
        public RewardV1 $reward,
        public RewardMessageV1 $message,
        public DateTimeInterface $redeemedAt,
        public ?string $userInput = null,
    ) {
    }
}
