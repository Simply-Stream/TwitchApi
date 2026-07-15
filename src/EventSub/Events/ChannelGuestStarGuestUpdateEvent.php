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
     * @param string      $broadcasterUserId    The non-host broadcaster user ID.
     * @param string      $broadcasterUserName  The non-host broadcaster display name.
     * @param string      $broadcasterUserLogin The non-host broadcaster login.
     * @param string      $sessionId            ID representing the unique session that was started.
     * @param string      $hostUserId           User ID of the host channel.
     * @param string      $hostUserName         The host display name.
     * @param string      $hostUserLogin        The host login.
     * @param string|null $moderatorUserId      The user ID of the moderator who updated the guest’s state (could be
     *                                          the host). Null if the update was performed by the guest.
     * @param string|null $moderatorUserName    The moderator display name. Null if the update was performed by the
     *                                          guest.
     * @param string|null $moderatorUserLogin   The moderator login. Null if the update was performed by the guest.
     * @param string|null $guestUserId          The user ID of the guest who transitioned states in the session. Null
     *                                          if the slot is now empty.
     * @param string|null $guestUserName        The guest display name. Null if the slot is now empty.
     * @param string|null $guestUserLogin       The guest login. Null if the slot is now empty.
     * @param string|null $slotId               The ID of the slot assignment the guest is assigned to. Null if the
     *                                          guest is in the INVITED, REMOVED, READY, or ACCEPTED state.
     * @param string|null $state                The current state of the user after the update. Null if the slot is
     *                                          now empty. Otherwise one of: invited, accepted, ready, backstage,
     *                                          live, removed.
     * @param bool|null   $hostVideoEnabled     Whether the host is allowing the slot’s video to be seen by
     *                                          participants. Null if the guest is not slotted.
     * @param bool|null   $hostAudioEnabled     Whether the host is allowing the slot’s audio to be heard by
     *                                          participants. Null if the guest is not slotted.
     * @param int|null    $hostVolume           Value between 0-100 representing the slot’s audio level. Null if the
     *                                          guest is not slotted.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $sessionId,
        public string $hostUserId,
        public string $hostUserName,
        public string $hostUserLogin,
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
