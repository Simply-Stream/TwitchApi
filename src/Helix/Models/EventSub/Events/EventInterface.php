<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

interface EventInterface
{
    public const AVAILABLE_EVENTS = [
        Subscriptions\ChannelBanSubscription::TYPE => ChannelBanEvent::class,
        Subscriptions\ChannelChatSettingsUpdateSubscription::TYPE => ChannelChatSettingsUpdateEvent::class,
        Subscriptions\ChannelPointsCustomRewardAddSubscription::TYPE => ChannelPointsCustomRewardAddEvent::class,
        Subscriptions\ChannelPointsCustomRewardRemoveSubscription::TYPE => ChannelPointsCustomRewardRemoveEvent::class,
        Subscriptions\ChannelPointsCustomRewardUpdateSubscription::TYPE => ChannelPointsCustomRewardUpdateEvent::class,
        Subscriptions\ChannelPointsCustomRewardRedemptionAddSubscription::TYPE => ChannelPointsCustomRewardRedemptionAddEvent::class,
        Subscriptions\ChannelPointsCustomRewardRedemptionUpdateSubscription::TYPE => ChannelPointsCustomRewardRedemptionUpdateEvent::class,
        Subscriptions\CharityDonationSubscription::TYPE => CharityDonationEvent::class,
        Subscriptions\CharityCampaignStartSubscription::TYPE => CharityCampaignStartEvent::class,
        Subscriptions\CharityCampaignProgressSubscription::TYPE => CharityCampaignProgressEvent::class,
        Subscriptions\CharityCampaignEndSubscription::TYPE => CharityCampaignEndEvent::class,
        Subscriptions\CharityCampaignStopSubscription::TYPE => CharityCampaignStopEvent::class,
        Subscriptions\ChannelCheerSubscription::TYPE => ChannelCheerEvent::class,
        Subscriptions\ChannelFollowSubscription::TYPE => ChannelFollowEvent::class,
        Subscriptions\GoalBeginSubscription::TYPE => GoalBeginEvent::class,
        Subscriptions\GoalProgressSubscription::TYPE => GoalProgressEvent::class,
        Subscriptions\GoalEndSubscription::TYPE => GoalEndEvent::class,
        Subscriptions\HypeTrainBeginSubscription::TYPE => HypeTrainBeginEvent::class,
        Subscriptions\HypeTrainProgressSubscription::TYPE => HypeTrainProgressEvent::class,
        Subscriptions\HypeTrainEndSubscription::TYPE => HypeTrainEndEvent::class,
        Subscriptions\ChannelModeratorAddSubscription::TYPE => ChannelModeratorAddEvent::class,
        Subscriptions\ChannelModeratorRemoveSubscription::TYPE => ChannelModeratorRemoveEvent::class,
        Subscriptions\ChannelPollBeginSubscription::TYPE => ChannelPollBeginEvent::class,
        Subscriptions\ChannelPollProgressSubscription::TYPE => ChannelPollProgressEvent::class,
        Subscriptions\ChannelPollEndSubscription::TYPE => ChannelPollEndEvent::class,
        Subscriptions\ChannelPredictionBeginSubscription::TYPE => ChannelPredictionBeginEvent::class,
        Subscriptions\ChannelPredictionProgressSubscription::TYPE => ChannelPredictionProgressEvent::class,
        Subscriptions\ChannelPredictionLockSubscription::TYPE => ChannelPredictionLockEvent::class,
        Subscriptions\ChannelPredictionEndSubscription::TYPE => ChannelPredictionEndEvent::class,
        Subscriptions\ChannelRaidSubscription::TYPE => ChannelRaidEvent::class,
        Subscriptions\ShieldModeBeginSubscription::TYPE => ShieldModeEvent::class,
        Subscriptions\ShieldModeEndSubscription::TYPE => ShieldModeEvent::class,
        Subscriptions\ShoutoutCreateSubscription::TYPE => ShoutoutCreateEvent::class,
        Subscriptions\ShoutoutReceiveSubscription::TYPE => ShoutoutReceiveEvent::class,
        Subscriptions\ChannelSubscribeSubscription::TYPE => ChannelSubscribeEvent::class,
        Subscriptions\ChannelSubscriptionEndSubscription::TYPE => ChannelSubscriptionEndEvent::class,
        Subscriptions\ChannelSubscriptionGiftSubscription::TYPE => ChannelSubscriptionGiftEvent::class,
        Subscriptions\ChannelSubscriptionMessageSubscription::TYPE => ChannelSubscriptionMessageEvent::class,
        Subscriptions\ChannelUnbanSubscription::TYPE => ChannelUnbanEvent::class,
        Subscriptions\ChannelUpdateSubscription::TYPE => ChannelUpdateEvent::class,
        Subscriptions\DropEntitlementGrantSubscription::TYPE => DropEntitlementGrantEvent::class,
        Subscriptions\ExtensionBitsTransactionCreateSubscription::TYPE => ExtensionBitsTransactionCreateEvent::class,
        Subscriptions\StreamOfflineSubscription::TYPE => StreamOfflineEvent::class,
        Subscriptions\StreamOnlineSubscription::TYPE => StreamOnlineEvent::class,
        Subscriptions\UserAuthorizationGrantSubscription::TYPE => UserAuthorizationGrantEvent::class,
        Subscriptions\UserAuthorizationRevokeSubscription::TYPE => UserAuthorizationRevokeEvent::class,
        Subscriptions\UserUpdateSubscription::TYPE => UserUpdateEvent::class,
    ];
}
