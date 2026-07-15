<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelUnbanRequestResolveCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.unban_request.resolve', version: '1', condition: ChannelUnbanRequestResolveCondition::class)]
final readonly class ChannelUnbanRequestResolveEvent implements EventInterface
{
    /**
     * @param string      $id                   The ID of the unban request.
     * @param string      $broadcasterUserId    The broadcaster’s user ID for the channel the unban request was
     *                                          updated for.
     * @param string      $broadcasterUserLogin The broadcaster’s login name.
     * @param string      $broadcasterUserName  The broadcaster’s display name.
     * @param string      $userId               User ID of user that requested to be unbanned.
     * @param string      $userLogin            The user’s login name.
     * @param string      $userName             The user’s display name.
     * @param string      $status               Dictates whether the unban request was approved or denied. Can be:
     *                                          - approved
     *                                          - canceled
     *                                          - denied
     * @param string|null $moderatorId          Optional. User ID of moderator who approved/denied the request.
     * @param string|null $moderatorLogin       Optional. The moderator’s login name.
     * @param string|null $moderatorName        Optional. The moderator’s display name.
     * @param string|null $resolutionText       Optional. Resolution text supplied by the mod/broadcaster upon
     *                                          approval/denial of the request.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $status,
        public ?string $moderatorId = null,
        public ?string $moderatorLogin = null,
        public ?string $moderatorName = null,
        public ?string $resolutionText = null,
    ) {
    }
}
