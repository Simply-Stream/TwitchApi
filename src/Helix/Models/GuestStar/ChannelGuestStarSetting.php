<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\GuestStar;

final readonly class ChannelGuestStarSetting
{
    /**
     * @param bool   $isModeratorSendLiveEnabled  Flag determining if Guest Star moderators have access to control
     *                                            whether a guest is live once assigned to a slot.
     * @param int    $slotCount                   Number of slots the Guest Star call interface will allow the host to
     *                                            add to a call. Required to be between 1 and 6.
     * @param bool   $isBrowserSourceAudioEnabled Flag determining if Browser Sources subscribed to sessions on this
     *                                            channel should output audio
     * @param string $groupLayout                 This setting determines how the guests within a session should be
     *                                            laid out within the browser source.
     * @param string $browserSourceToken          View only token to generate browser source URLs
     */
    public function __construct(
        public bool $isModeratorSendLiveEnabled,
        public int $slotCount,
        public bool $isBrowserSourceAudioEnabled,
        public string $groupLayout,
        public string $browserSourceToken,
    ) {
    }
}
