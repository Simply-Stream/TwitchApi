<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

final readonly class UpdateGuestStarSlotRequest
{
    /**
     * @param string      $broadcasterId     The ID of the broadcaster running the Guest Star session.
     * @param string      $moderatorId       The ID of the broadcaster or a user that has permission to moderate the
     *                                       broadcaster’s chat room. This ID must match the user_id in the user access
     *                                       token.
     * @param string      $sessionId         The ID of the Guest Star session in which to update slot settings.
     * @param string      $sourceSlotId      The slot assignment previously assigned to a user.
     * @param string|null $destinationSlotId The slot to move this user assignment to. If the destination slot is
     *                                       occupied, the user assigned will be swapped into source_slot_id.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public string $sessionId,
        public string $sourceSlotId,
        public ?string $destinationSlotId = null,
    ) {
    }
}
