<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelSuspiciousUserMessageCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\SuspiciousUser\SuspiciousUserMessage;

#[EventSubSubscription(type: 'channel.suspicious_user.message', version: '1', condition: ChannelSuspiciousUserMessageCondition::class)]
final readonly class ChannelSuspiciousUserMessageEvent implements EventInterface
{
    /**
     * @param string                $broadcasterUserId    The ID of the channel where the treatment for a suspicious
     *                                                    user was updated.
     * @param string                $broadcasterUserName  The display name of the channel.
     * @param string                $broadcasterUserLogin The login of the channel.
     * @param string                $userId               The user ID of the user that sent the message.
     * @param string                $userName             The user name of the user that sent the message.
     * @param string                $userLogin            The user login of the user that sent the message.
     * @param string                $lowTrustStatus       The status set for the suspicious user. One of: none,
     *                                                    active_monitoring, restricted.
     * @param string[]              $sharedBanChannelIds  A list of channel IDs where the suspicious user is also
     *                                                    banned.
     * @param string[]              $types                User types (if any) that apply to the suspicious user. Can
     *                                                    be: manually_added, ban_evader, banned_in_shared_channel.
     * @param string                $banEvasionEvaluation A ban evasion likelihood value (if any) applied to the user
     *                                                    automatically by Twitch. One of: unknown, possible, likely.
     * @param SuspiciousUserMessage $message              The structured chat message.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $userId,
        public string $userName,
        public string $userLogin,
        public string $lowTrustStatus,
        public array $sharedBanChannelIds,
        public array $types,
        public string $banEvasionEvaluation,
        public SuspiciousUserMessage $message,
    ) {
    }
}
