<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

interface Subscriptions
{
    public const MAP = [
        ChannelAdBreakBeginSubscription::TYPE => ChannelAdBreakBeginSubscription::class,
        ChannelBanSubscription::TYPE => ChannelBanSubscription::class,
        ChannelChatClearSubscription::TYPE => ChannelChatClearSubscription::class,
        ChannelChatClearUserMessagesSubscription::TYPE => ChannelChatClearUserMessagesSubscription::class,
        ChannelChatMessageDeleteSubscription::TYPE => ChannelChatMessageDeleteSubscription::class,
        ChannelChatNotificationSubscription::TYPE => ChannelChatNotificationSubscription::class,
        ChannelCheerSubscription::TYPE => ChannelCheerSubscription::class,
        ChannelFollowSubscription::TYPE => ChannelFollowSubscription::class,
        ChannelGuestStarGuestUpdateSubscription::TYPE => ChannelGuestStarGuestUpdateSubscription::class,
        ChannelGuestStarSessionBeginSubscription::TYPE => ChannelGuestStarSessionBeginSubscription::class,
        ChannelGuestStarSessionEndSubscription::TYPE => ChannelGuestStarSessionEndSubscription::class,
        ChannelGuestStarSettingsUpdateSubscription::TYPE => ChannelGuestStarSettingsUpdateSubscription::class,
        ChannelModeratorAddSubscription::TYPE => ChannelModeratorAddSubscription::class,
        ChannelModeratorRemoveSubscription::TYPE => ChannelModeratorRemoveSubscription::class,
        ChannelPointsCustomRewardAddSubscription::TYPE => ChannelPointsCustomRewardAddSubscription::class,
        ChannelPointsCustomRewardRedemptionAddSubscription::TYPE => ChannelPointsCustomRewardRedemptionAddSubscription::class,
        ChannelPointsCustomRewardRedemptionUpdateSubscription::TYPE => ChannelPointsCustomRewardRedemptionUpdateSubscription::class,
        ChannelPointsCustomRewardRemoveSubscription::TYPE => ChannelPointsCustomRewardRemoveSubscription::class,
        ChannelPointsCustomRewardUpdateSubscription::TYPE => ChannelPointsCustomRewardUpdateSubscription::class,
        ChannelPollBeginSubscription::TYPE => ChannelPollBeginSubscription::class,
        ChannelPollProgressSubscription::TYPE => ChannelPollProgressSubscription::class,
        ChannelPollEndSubscription::TYPE => ChannelPollEndSubscription::class,
        ChannelPredictionBeginSubscription::TYPE => ChannelPredictionBeginSubscription::class,
        ChannelPredictionProgressSubscription::TYPE => ChannelPredictionProgressSubscription::class,
        ChannelPredictionLockSubscription::TYPE => ChannelPredictionLockSubscription::class,
        ChannelPredictionEndSubscription::TYPE => ChannelPredictionEndSubscription::class,
        ChannelRaidSubscription::TYPE => ChannelRaidSubscription::class,
        ChannelSubscribeSubscription::TYPE => ChannelSubscribeSubscription::class,
        ChannelSubscriptionEndSubscription::TYPE => ChannelSubscriptionEndSubscription::class,
        ChannelSubscriptionGiftSubscription::TYPE => ChannelSubscriptionGiftSubscription::class,
        ChannelSubscriptionMessageSubscription::TYPE => ChannelSubscriptionMessageSubscription::class,
        ChannelUnbanSubscription::TYPE => ChannelUnbanSubscription::class,
        ChannelUpdateSubscription::TYPE => ChannelUpdateSubscription::class,
        DropEntitlementGrantSubscription::TYPE => DropEntitlementGrantSubscription::class,
        ExtensionBitsTransactionCreateSubscription::TYPE => ExtensionBitsTransactionCreateSubscription::class,
        GoalsBeginSubscription::TYPE => GoalsBeginSubscription::class,
        GoalsProgressSubscription::TYPE => GoalsProgressSubscription::class,
        GoalsEndSubscription::TYPE => GoalsEndSubscription::class,
        HypeTrainBeginSubscription::TYPE => HypeTrainBeginSubscription::class,
        HypeTrainProgressSubscription::TYPE => HypeTrainProgressSubscription::class,
        HypeTrainEndSubscription::TYPE => HypeTrainEndSubscription::class,
        ShieldModeBeginSubscription::TYPE => ShieldModeBeginSubscription::class,
        ShieldModeEndSubscription::TYPE => ShieldModeEndSubscription::class,
        ShoutoutCreateSubscription::TYPE => ShoutoutCreateSubscription::class,
        ShoutoutReceivedSubscription::TYPE => ShoutoutReceivedSubscription::class,
        StreamOfflineSubscription::TYPE => StreamOfflineSubscription::class,
        StreamOnlineSubscription::TYPE => StreamOnlineSubscription::class,
        UserAuthorizationGrantSubscription::TYPE => UserAuthorizationGrantSubscription::class,
        UserAuthorizationRevokeSubscription::TYPE => UserAuthorizationRevokeSubscription::class,
        UserUpdateSubscription::TYPE => UserUpdateSubscription::class
    ];
}
