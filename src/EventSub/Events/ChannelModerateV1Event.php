<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelModerateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\AutomodTerms;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Ban;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Delete;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Followers;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Mod;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Raid;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Slow;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Timeout;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Unban;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\UnbanRequest;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Unraid;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Untimeout;
use SimplyStream\TwitchApi\EventSub\Events\Moderate\Vip;

#[EventSubSubscription(type: 'channel.moderate', version: '1', condition: ChannelModerateCondition::class)]
final readonly class ChannelModerateV1Event implements EventInterface
{
    /**
     * @param string            $broadcasterUserId          The ID of the broadcaster.
     * @param string            $broadcasterUserLogin       The login of the broadcaster.
     * @param string            $broadcasterUserName        The user name of the broadcaster.
     * @param string            $moderatorUserId            The ID of the moderator who performed the action.
     * @param string            $moderatorUserLogin         The login of the moderator.
     * @param string            $moderatorUserName          The user name of the moderator.
     * @param string            $action                     The type of action. See the Twitch docs for the full list
     *                                                      of possible values.
     * @param string|null       $sourceBroadcasterUserId    The channel in which the action originally occurred. Same
     *                                                      as broadcasterUserId if not in shared chat.
     * @param string|null       $sourceBroadcasterUserLogin The login of the source channel. Same as
     *                                                      broadcasterUserLogin if not in shared chat.
     * @param string|null       $sourceBroadcasterUserName  The user name of the source channel. Null when the action
     *                                                      happens in the broadcaster’s own channel.
     * @param Followers|null    $followers                  Optional. Metadata for the followers command.
     * @param Slow|null         $slow                       Optional. Metadata for the slow command.
     * @param Vip|null          $vip                        Optional. Metadata for the vip command.
     * @param Vip|null          $unvip                      Optional. Metadata for the unvip command.
     * @param Mod|null          $mod                        Optional. Metadata for the mod command.
     * @param Mod|null          $unmod                      Optional. Metadata for the unmod command.
     * @param Ban|null          $ban                        Optional. Metadata for the ban command.
     * @param Unban|null        $unban                      Optional. Metadata for the unban command.
     * @param Timeout|null      $timeout                    Optional. Metadata for the timeout command.
     * @param Untimeout|null    $untimeout                  Optional. Metadata for the untimeout command.
     * @param Raid|null         $raid                       Optional. Metadata for the raid command.
     * @param Unraid|null       $unraid                     Optional. Metadata for the unraid command.
     * @param Delete|null       $delete                     Optional. Metadata for the delete command.
     * @param AutomodTerms|null $automodTerms               Optional. Metadata for the automod terms changes.
     * @param UnbanRequest|null $unbanRequest               Optional. Metadata for an unban request.
     * @param Ban|null          $sharedChatBan              Optional. Same shape as $ban, for a shared-chat action.
     * @param Unban|null        $sharedChatUnban            Optional. Same shape as $unban.
     * @param Timeout|null      $sharedChatTimeout          Optional. Same shape as $timeout.
     * @param Untimeout|null    $sharedChatUntimeout        Optional. Same shape as $untimeout.
     * @param Delete|null       $sharedChatDelete           Optional. Same shape as $delete.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public string $action,
        public ?string $sourceBroadcasterUserId = null,
        public ?string $sourceBroadcasterUserLogin = null,
        public ?string $sourceBroadcasterUserName = null,
        public ?Followers $followers = null,
        public ?Slow $slow = null,
        public ?Vip $vip = null,
        public ?Vip $unvip = null,
        public ?Mod $mod = null,
        public ?Mod $unmod = null,
        public ?Ban $ban = null,
        public ?Unban $unban = null,
        public ?Timeout $timeout = null,
        public ?Untimeout $untimeout = null,
        public ?Raid $raid = null,
        public ?Unraid $unraid = null,
        public ?Delete $delete = null,
        public ?AutomodTerms $automodTerms = null,
        public ?UnbanRequest $unbanRequest = null,
        public ?Ban $sharedChatBan = null,
        public ?Unban $sharedChatUnban = null,
        public ?Timeout $sharedChatTimeout = null,
        public ?Untimeout $sharedChatUntimeout = null,
        public ?Delete $sharedChatDelete = null,
    ) {
    }
}
