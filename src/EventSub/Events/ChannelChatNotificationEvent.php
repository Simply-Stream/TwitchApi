<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatNotificationCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\Announcement;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\Badge;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\BitsBadgeTier;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\CharityDonation;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\CommunityGiftSubscription;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\GiftPaidUpgrade;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\GiftSubscription;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\Modiversary;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\PayItForward;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\PrimePaidUpgrade;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\Raid;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\Resubscription;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\Subscription;
use SimplyStream\TwitchApi\EventSub\Events\ChatNotification\WatchStreak;
use SimplyStream\TwitchApi\EventSub\Shared\Message;

#[EventSubSubscription(type: 'channel.chat.notification', version: '1', condition: ChannelChatNotificationCondition::class)]
final readonly class ChannelChatNotificationEvent implements EventInterface
{
    /**
     * @param string                                                              $broadcasterUserId
     * @param string                                                              $broadcasterUserName
     * @param string                                                              $broadcasterUserLogin
     * @param string                                                              $chatterUserId
     * @param string                                                              $chatterUserName
     * @param string                                                              $chatterUserLogin
     * @param bool                                                                $chatterIsAnonymous
     * @param string                                                              $color
     * @param Badge[]                                                             $badges
     * @param string                                                              $systemMessage
     * @param string                                                              $messageId
     * @param \SimplyStream\TwitchApi\EventSub\Events\SubscriptionMessage\Message $message
     * @param string                                                              $noticeType                 One of: sub, resub, sub_gift,
     *                                                                   community_sub_gift, gift_paid_upgrade,
     *                                                                   prime_paid_upgrade, raid, unraid,
     *                                                                   pay_it_forward, announcement, bits_badge_tier,
     *                                                                   charity_donation, watch_streak, modiversary,
     *                                                                   shared_chat_sub, shared_chat_resub,
     *                                                                   shared_chat_sub_gift,
     *                                                                   shared_chat_community_sub_gift,
     *                                                                   shared_chat_gift_paid_upgrade,
     *                                                                   shared_chat_prime_paid_upgrade,
     *                                                                   shared_chat_raid, shared_chat_pay_it_forward,
     *                                                                   shared_chat_announcement,
     *                                                                   shared_chat_modiversary, unknown.
     * @param Subscription|null              $sub
     * @param Resubscription|null            $resub
     * @param GiftSubscription|null          $subGift
     * @param CommunityGiftSubscription|null $communitySubGift
     * @param GiftPaidUpgrade|null           $giftPaidUpgrade
     * @param PrimePaidUpgrade|null          $primePaidUpgrade
     * @param Raid|null                      $raid
     * @param array|null                     $unraid                     Empty payload if notice_type is unraid, else
     *                                                                   null.
     * @param PayItForward|null              $payItForward
     * @param Announcement|null              $announcement
     * @param CharityDonation|null           $charityDonation
     * @param BitsBadgeTier|null             $bitsBadgeTier
     * @param WatchStreak|null               $watchStreak
     * @param Modiversary|null               $modiversary
     * @param string|null                    $sourceBroadcasterUserId    Shared-chat source channel; null in the
     *                                                                   broadcaster’s own channel.
     * @param string|null                    $sourceBroadcasterUserName
     * @param string|null                    $sourceBroadcasterUserLogin
     * @param string|null                    $sourceMessageId
     * @param Badge[]|null                   $sourceBadges
     * @param bool|null                      $isSourceOnly
     * @param Subscription|null              $sharedChatSub              Same shape as $sub, for a shared-chat notice.
     * @param Resubscription|null            $sharedChatResub
     * @param GiftSubscription|null          $sharedChatSubGift
     * @param CommunityGiftSubscription|null $sharedChatCommunitySubGift
     * @param GiftPaidUpgrade|null           $sharedChatGiftPaidUpgrade
     * @param PrimePaidUpgrade|null          $sharedChatPrimePaidUpgrade
     * @param Raid|null                      $sharedChatRaid
     * @param PayItForward|null              $sharedChatPayItForward
     * @param Announcement|null              $sharedChatAnnouncement
     * @param Modiversary|null               $sharedChatModiversary
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $chatterUserId,
        public string $chatterUserName,
        public string $chatterUserLogin,
        public bool $chatterIsAnonymous,
        public string $color,
        public array $badges,
        public string $systemMessage,
        public string $messageId,
        public Message $message,
        public string $noticeType,
        public ?Subscription $sub = null,
        public ?Resubscription $resub = null,
        public ?GiftSubscription $subGift = null,
        public ?CommunityGiftSubscription $communitySubGift = null,
        public ?GiftPaidUpgrade $giftPaidUpgrade = null,
        public ?PrimePaidUpgrade $primePaidUpgrade = null,
        public ?Raid $raid = null,
        public ?array $unraid = null,
        public ?PayItForward $payItForward = null,
        public ?Announcement $announcement = null,
        public ?CharityDonation $charityDonation = null,
        public ?BitsBadgeTier $bitsBadgeTier = null,
        public ?WatchStreak $watchStreak = null,
        public ?Modiversary $modiversary = null,
        public ?string $sourceBroadcasterUserId = null,
        public ?string $sourceBroadcasterUserName = null,
        public ?string $sourceBroadcasterUserLogin = null,
        public ?string $sourceMessageId = null,
        public ?array $sourceBadges = null,
        public ?bool $isSourceOnly = null,
        public ?Subscription $sharedChatSub = null,
        public ?Resubscription $sharedChatResub = null,
        public ?GiftSubscription $sharedChatSubGift = null,
        public ?CommunityGiftSubscription $sharedChatCommunitySubGift = null,
        public ?GiftPaidUpgrade $sharedChatGiftPaidUpgrade = null,
        public ?PrimePaidUpgrade $sharedChatPrimePaidUpgrade = null,
        public ?Raid $sharedChatRaid = null,
        public ?PayItForward $sharedChatPayItForward = null,
        public ?Announcement $sharedChatAnnouncement = null,
        public ?Modiversary $sharedChatModiversary = null,
    ) {
    }
}
