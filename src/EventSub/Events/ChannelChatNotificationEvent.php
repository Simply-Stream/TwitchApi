<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelChatNotificationCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\Announcement;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\Badge;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\BitsBadgeTier;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\CharityDonation;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\CommunityGiftSubscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\GiftPaidUpgrade;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\GiftSubscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\Message;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\PayItForward;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\PrimePaidUpgrade;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\Raid;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\Resubscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications\Subscription;

#[EventSubSubscription(type: 'channel.chat.notification', version: '1', condition: ChannelChatNotificationCondition::class)]
final readonly class ChannelChatNotificationEvent
{
    /**
     * @param string                         $broadcasterUserId                  The broadcaster user ID.
     * @param string                         $broadcasterUserName                The broadcaster display name.
     * @param string                         $broadcasterUserLogin               The broadcaster login.
     * @param string                         $chatterUserId                      The user ID of the user that sent the
     *                                                                           message.
     * @param string                         $chatterUserName                    The user name of the user that sent
     *                                                                           the message.
     * @param string                         $chatterUserLogin                   The user login of the user that sent
     *                                                                           the message.
     * @param bool                           $chatterIsAnonymous                 Whether or not the chatter is
     *                                                                           anonymous.
     * @param string                         $color                              The color of the user’s name in the
     *                                                                           chat room.
     * @param Badge[]                        $badges                             List of chat badges.
     * @param string                         $systemMessage                      The message Twitch shows in the chat
     *                                                                           room for this notice.
     * @param string                         $messageId                          A UUID that identifies the message.
     * @param Message                        $message                            The structured chat message
     * @param string                         $noticeType                         The type of notice. Possible values
     *                                                                           are:
     *                                                                           - sub
     *                                                                           - resub
     *                                                                           - sub_gift
     *                                                                           - community_sub_gift
     *                                                                           - gift_paid_upgrade
     *                                                                           - prime_paid_upgrade
     *                                                                           - raid
     *                                                                           - unraid
     *                                                                           - pay_it_forward
     *                                                                           - announcement
     *                                                                           - bits_badge_tier
     *                                                                           - charity_donation
     * @param Subscription|null              $sub                                Information about the sub event. Null
     *                                                                           if notice_type is not sub.
     * @param Resubscription|null            $resub                              Information about the resub event.
     *                                                                           Null if notice_type is not resub.
     * @param GiftSubscription|null          $subGift                            Information about the gift sub event.
     *                                                                           Null if notice_type is not sub_gift.
     * @param CommunityGiftSubscription|null $communitySubGift                   Information about the community gift
     *                                                                           sub event. Null if notice_type is not
     *                                                                           community_sub_gift.
     * @param GiftPaidUpgrade|null           $giftPaidUpgrade                    Information about the community gift
     *                                                                           paid upgrade event. Null if
     *                                                                           notice_type is not gift_paid_upgrade.
     * @param PrimePaidUpgrade|null          $primePaidUpgrade                   Information about the Prime gift paid
     *                                                                           upgrade event. Null if notice_type is
     *                                                                           not prime_paid_upgrade.
     * @param Raid|null                      $raid                               Information about the raid event. Null
     *                                                                           if notice_type is not raid.
     * @param array|null                     $unraid                             Returns an empty payload if
     *                                                                           notice_type is unraid, otherwise
     *                                                                           returns null.
     * @param PayItForward|null              $payItForward                       Information about the pay it forward
     *                                                                           event. Null if notice_type is not
     *                                                                           pay_it_forward.
     * @param Announcement|null              $announcement                       Information about the announcement
     *                                                                           event. Null if notice_type is not
     *                                                                           announcement
     * @param CharityDonation|null           $charityDonation                    Information about the charity donation
     *                                                                           event. Null if notice_type is not
     *                                                                           charity_donation.
     * @param BitsBadgeTier|null             $bitsBadgeTier                      Information about the bits badge tier
     *                                                                           event. Null if notice_type is not
     *                                                                           bits_badge_tier.
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
        public ?Notifications\Subscription $sub = null,
        public ?Notifications\Resubscription $resub = null,
        public ?Notifications\GiftSubscription $subGift = null,
        public ?Notifications\CommunityGiftSubscription $communitySubGift = null,
        public ?Notifications\GiftPaidUpgrade $giftPaidUpgrade = null,
        public ?Notifications\PrimePaidUpgrade $primePaidUpgrade = null,
        public ?Notifications\Raid $raid = null,
        public ?array $unraid = null,
        public ?Notifications\PayItForward $payItForward = null,
        public ?Notifications\Announcement $announcement = null,
        public ?Notifications\CharityDonation $charityDonation = null,
        public ?Notifications\BitsBadgeTier $bitsBadgeTier = null,
    ) {
    }
}
