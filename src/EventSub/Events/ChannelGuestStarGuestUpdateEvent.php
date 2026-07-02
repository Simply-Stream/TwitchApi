<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelGuestStarGuestUpdateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.guest_star_guest.update', version: 'beta', condition: ChannelGuestStarGuestUpdateCondition::class)]
final readonly class ChannelGuestStarGuestUpdateEvent implements EventInterface
{
    /**
     * @param string      $broadcasterUserId    The broadcaster user ID
     * @param string      $broadcasterUserName  The broadcaster display name
     * @param string      $broadcasterUserLogin The broadcaster login
     * @param string      $sessionId            ID representing the unique session that was started.
     * @param string|null $moderatorUserId      The user ID of the moderator who updated the guest’s state (could be
     *                                          the host). null if the update was performed by the guest.
     * @param string|null $moderatorUserName    The moderator display name. null if the update was performed by the
     *                                          guest.
     * @param string|null $moderatorUserLogin   The moderator login. null if the update was performed by the guest.
     * @param string|null $guestUserId          The user ID of the guest who transitioned states in the session. null
     *                                          if the slot is now empty.
     * @param string|null $guestUserName        The guest display name. null if the slot is now empty.
     * @param string|null $guestUserLogin       The guest login. null if the slot is now empty.
     * @param string|null $slotId               The ID of the slot assignment the guest is assigned to. null if the
     *                                          guest is in the INVITED, REMOVED, READY, or ACCEPTED state.
     * @param string|null $state                The current state of the user after the update has taken place. null if
     *                                          the slot is now empty. Can otherwise be one of the following:
     *                                          - invited — The guest has transitioned to the invite queue. This can
     *                                          take place when the guest was previously assigned a slot, but have been
     *                                          removed from the call and are sent back to the invite queue.
     *                                          - accepted — The guest has accepted the invite and is currently in the
     *                                          process of setting up to join the session.
     *                                          - ready — The guest has signaled they are ready and can be assigned a
     *                                          slot.
     *                                          - backstage — The guest has been assigned a slot in the session, but is
     *                                          not currently seen live in the broadcasting software.
     *                                          - live — The guest is now live in the host's broadcasting software.
     *                                          - removed — The guest was removed from the call or queue.
     *                                          -- accepted — The guest has accepted the invite to the call.
     * @param bool|null   $hostVideoEnabled     Flag that signals whether the host is allowing the slot’s video to be
     *                                          seen by participants within the session. null if the guest is not
     *                                          slotted.
     * @param bool|null   $hostAudioEnabled     Flag that signals whether the host is allowing the slot’s audio to be
     *                                          heard by participants within the session. null if the guest is not
     *                                          slotted.
     * @param int|null    $hostVolume           Value between 0-100 that represents the slot’s audio level as heard by
     *                                          participants within the session. null if the guest is not slotted.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $sessionId,
        public ?string $moderatorUserId = null,
        public ?string $moderatorUserName = null,
        public ?string $moderatorUserLogin = null,
        public ?string $guestUserId = null,
        public ?string $guestUserName = null,
        public ?string $guestUserLogin = null,
        public ?string $slotId = null,
        public ?string $state = null,
        public ?bool $hostVideoEnabled = null,
        public ?bool $hostAudioEnabled = null,
        public ?int $hostVolume = null,
    ) {
    }
}
