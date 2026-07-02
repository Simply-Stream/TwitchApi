<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelGuestStarSettingsUpdateCondition;

#[EventSubSubscription(type: 'channel.guest_star_settings.update', version: '1', condition: ChannelGuestStarSettingsUpdateCondition::class)]
final readonly class ChannelGuestStarSettingsUpdateEvent
{
    /**
     * @param string $broadcasterUserId           User ID of the host channel
     * @param string $broadcasterUserName         The broadcaster display name
     * @param string $broadcasterUserLogin        The broadcaster login
     * @param bool   $isModeratorSendLiveEnabled  Flag determining if Guest Star moderators have access to control
     *                                            whether a guest is live once assigned to a slot.
     * @param int    $slotCount                   Number of slots the Guest Star call interface will allow the host to
     *                                            add to a call.
     * @param bool   $isBrowserSourceAudioEnabled Flag determining if browser sources subscribed to sessions on this
     *                                            channel should output audio
     * @param string $groupLayout                 This setting determines how the guests within a session should be
     *                                            laid out within a group browser source. Can be one of the following
     *                                            values:
     *                                            - tiled — All live guests are tiled within the browser source with
     *                                            the same size.
     *                                            - screenshare — All live guests are tiled within the browser source
     *                                            with the same size. If there is an active screen share, it is sized
     *                                            larger than the other guests.
     *                                            - horizontal_top — Indicates the group layout will contain all
     *                                            participants in a top-aligned horizontal stack.
     *                                            - horizontal_bottom — Indicates the group layout will contain all
     *                                            participants in a bottom-aligned horizontal stack.
     *                                            - vertical_left — Indicates the group layout will contain all
     *                                            participants in a left-aligned vertical stack.
     *                                            - vertical_right — Indicates the group layout will contain all
     *                                            participants in a right-aligned vertical stack.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public bool $isModeratorSendLiveEnabled,
        public int $slotCount,
        public bool $isBrowserSourceAudioEnabled,
        public string $groupLayout
    ) {
    }
}
