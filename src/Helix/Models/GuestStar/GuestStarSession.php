<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\GuestStar;

use DateTimeInterface;

final readonly class GuestStarSession
{
    /**
     * @param string             $id              ID uniquely representing the Guest Star session.
     * @param list<mixed>        $guests          List of guests currently interacting with the Guest Star session.
     *                                            Note: Twitch does not document a concrete shape for this field.
     * @param string             $slotId          ID representing this guest’s slot assignment.
     *                                            - Host is always in slot "0"
     *                                            - Guests are assigned consecutive IDs (e.g. "1", "2", "3", etc.)
     *                                            - Screen Share is represented as a special guest with the ID
     *                                            "SCREENSHARE"
     * @param bool               $isLive          Flag determining whether the guest is visible in the browser source
     *                                            in the host’s streaming software.
     * @param string             $userId          User ID of the guest assigned to this slot.
     * @param string             $userDisplayName Display name of the guest assigned to this slot.
     * @param string             $userLogin       Login of the guest assigned to this slot.
     * @param int                $volume          Value from 0 to 100 representing the host’s volume setting for this
     *                                            guest.
     * @param DateTimeInterface  $assignedAt      Timestamp when this guest was assigned a slot in the session.
     * @param MediaSettings      $audioSettings   Information about the guest’s audio settings
     * @param MediaSettings      $videoSettings   Information about the guest’s video settings
     */
    public function __construct(
        public string $id,
        public array $guests,
        public string $slotId,
        public bool $isLive,
        public string $userId,
        public string $userDisplayName,
        public string $userLogin,
        public int $volume,
        public DateTimeInterface $assignedAt,
        public MediaSettings $audioSettings,
        public MediaSettings $videoSettings,
    ) {
    }
}
