<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\AssignGuestStarSlotRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\CreateGuestStarSessionRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\DeleteGuestStarInviteRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\DeleteGuestStarSlotRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\EndGuestStarSessionRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\GetChannelGuestStarSettingsRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\GetGuestStarInvitesRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\GetGuestStarSessionRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\SendGuestStarInviteRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\UpdateChannelGuestStarSettingsRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\UpdateGuestStarSlotRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Request\UpdateGuestStarSlotSettingsRequest;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Response\ChannelGuestStarSettingsResponse;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Response\GuestStarInvitesResponse;
use SimplyStream\TwitchApi\Helix\Api\GuestStar\Response\GuestStarSessionResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class GuestStarApi extends AbstractApi
{
    private const string BASE_PATH = 'guest_star';

    /**
     * (BETA) Gets the channel settings for configuration of the Guest Star feature for a particular host.
     *
     * Authorization
     * - Query parameter moderator_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:read:guest_star, channel:manage:guest_star, moderator:read:guest_star or
     * moderator:manage:guest_star
     *
     * URL
     * GET https://api.twitch.tv/helix/guest_star/channel_settings
     *
     * @param AccessTokenInterface               $accessToken Requires OAuth Scope: channel:read:guest_star,
     *                                                        channel:manage:guest_star, moderator:read:guest_star or
     *                                                        moderator:manage:guest_star
     */
    public function getChannelGuestStarSettings(
        GetChannelGuestStarSettingsRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelGuestStarSettingsResponse {
        return $this->get(
            self::BASE_PATH . '/channel_settings',
            ChannelGuestStarSettingsResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * (BETA) Mutates the channel settings for configuration of the Guest Star feature for a particular host.
     *
     * Authorization
     * - Query parameter broadcaster_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star
     *
     * URL
     * PUT https://api.twitch.tv/helix/guest_star/channel_settings
     *
     * @param AccessTokenInterface                  $accessToken Requires OAuth Scope: channel:manage:guest_star
     */
    public function updateChannelGuestStarSettings(
        UpdateChannelGuestStarSettingsRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                ...$this->normalizer->normalize($request->settings),
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        $this->putWithoutResponse(self::BASE_PATH . '/channel_settings', $accessToken, query: $query);
    }

    /**
     * (BETA) Gets information about an ongoing Guest Star session for a particular host.
     *
     * Authorization
     * - Query parameter broadcaster_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star
     *
     * URL
     * GET https://api.twitch.tv/helix/guest_star/session
     *
     * @param AccessTokenInterface       $accessToken Requires OAuth Scope: channel:manage:guest_star
     */
    public function getGuestStarSession(
        GetGuestStarSessionRequest $request,
        AccessTokenInterface $accessToken,
    ): GuestStarSessionResponse {
        return $this->get(
            self::BASE_PATH . '/session',
            GuestStarSessionResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * (BETA) Programmatically creates a Guest Star session on behalf of the broadcaster. Requires the broadcaster to
     * be present in the call interface, or the call will be ended automatically.
     *
     * Authorization
     * - Query parameter broadcaster_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star
     *
     * URL
     * POST https://api.twitch.tv/helix/guest_star/session
     *
     * @param AccessTokenInterface          $accessToken Requires OAuth Scope: channel:manage:guest_star
     */
    public function createGuestStarSession(
        CreateGuestStarSessionRequest $request,
        AccessTokenInterface $accessToken,
    ): GuestStarSessionResponse {
        return $this->post(
            self::BASE_PATH . '/session',
            GuestStarSessionResponse::class,
            $accessToken,
            query: [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }

    /**
     * (BETA) Programmatically ends a Guest Star session on behalf of the broadcaster. Performs the same action as if
     * the host clicked the “End Call” button in the Guest Star UI.
     *
     * Authorization
     * - Query parameter broadcaster_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star
     *
     * URL
     * DELETE https://api.twitch.tv/helix/guest_star/session
     *
     * @param AccessTokenInterface       $accessToken Requires OAuth Scope: channel:manage:guest_star
     */
    public function endGuestStarSession(
        EndGuestStarSessionRequest $request,
        AccessTokenInterface $accessToken,
    ): GuestStarSessionResponse {
        return $this->deleteWithResponse(
            self::BASE_PATH . '/session',
            GuestStarSessionResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'session_id'     => $request->sessionId,
            ],
        );
    }

    /**
     * (BETA) Provides the caller with a list of pending invites to a Guest Star session, including the invitee’s ready
     * status while joining the waiting room.
     *
     * Authorization
     * - Query parameter broadcaster_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:read:guest_star, channel:manage:guest_star, moderator:read:guest_star or
     * moderator:manage:guest_star
     *
     * URL
     * GET https://api.twitch.tv/helix/guest_star/invites
     *
     * @param AccessTokenInterface       $accessToken Requires OAuth Scope: channel:read:guest_star,
     *                                               channel:manage:guest_star, moderator:read:guest_star or
     *                                               moderator:manage:guest_star
     */
    public function getGuestStarInvites(
        GetGuestStarInvitesRequest $request,
        AccessTokenInterface $accessToken,
    ): GuestStarInvitesResponse {
        return $this->get(
            self::BASE_PATH . '/invites',
            GuestStarInvitesResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'session_id'     => $request->sessionId,
            ],
        );
    }

    /**
     * (BETA) Sends an invite to a specified guest on behalf of the broadcaster for a Guest Star session in progress.
     *
     * Authorization
     * - Query parameter moderator_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star or moderator:manage:guest_star
     *
     * URL
     * POST https://api.twitch.tv/helix/guest_star/invites
     *
     * @param AccessTokenInterface       $accessToken Requires OAuth Scope: channel:manage:guest_star or
     *                                               moderator:manage:guest_star
     */
    public function sendGuestStarInvite(
        SendGuestStarInviteRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/invites',
            $accessToken,
            query: [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'session_id'     => $request->sessionId,
                'guest_id'       => $request->guestId,
            ],
        );
    }

    /**
     * (BETA) Revokes a previously sent invite for a Guest Star session.
     *
     * Authorization
     * - Query parameter moderator_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star or moderator:manage:guest_star
     *
     * URL
     * DELETE https://api.twitch.tv/helix/guest_star/invites
     *
     * @param AccessTokenInterface         $accessToken Requires OAuth Scope: channel:manage:guest_star or
     *                                                 moderator:manage:guest_star
     */
    public function deleteGuestStarInvite(
        DeleteGuestStarInviteRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH . '/invites',
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'session_id'     => $request->sessionId,
                'guest_id'       => $request->guestId,
            ],
        );
    }

    /**
     * (BETA) Allows a previously invited user to be assigned a slot within the active Guest Star session, once that
     * guest has indicated they are ready to join.
     *
     * Authorization
     * - Query parameter moderator_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star or moderator:manage:guest_star
     *
     * URL
     * POST https://api.twitch.tv/helix/guest_star/slot
     *
     * @param AccessTokenInterface       $accessToken Requires OAuth Scope: channel:manage:guest_star or
     *                                               moderator:manage:guest_star
     */
    public function assignGuestStarSlot(
        AssignGuestStarSlotRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/slot',
            $accessToken,
            query: [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'session_id'     => $request->sessionId,
                'guest_id'       => $request->guestId,
                'slot_id'        => $request->slotId,
            ],
        );
    }

    /**
     * (BETA) Allows a user to update the assigned slot for a particular user within the active Guest Star session.
     *
     * Authorization
     * - Query parameter moderator_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star or moderator:manage:guest_star
     *
     * URL
     * PATCH https://api.twitch.tv/helix/guest_star/slot
     *
     * @param AccessTokenInterface       $accessToken Requires OAuth Scope: channel:manage:guest_star or
     *                                               moderator:manage:guest_star
     */
    public function updateGuestStarSlot(
        UpdateGuestStarSlotRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $query = array_filter(
            [
                'broadcaster_id'      => $request->broadcasterId,
                'moderator_id'        => $request->moderatorId,
                'session_id'          => $request->sessionId,
                'source_slot_id'      => $request->sourceSlotId,
                'destination_slot_id' => $request->destinationSlotId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        $this->patchWithoutResponse(self::BASE_PATH . '/slot', $accessToken, query: $query);
    }

    /**
     * (BETA) Allows a caller to remove a slot assignment from a user participating in an active Guest Star session.
     * This revokes their access to the session immediately and disables their access to publish or subscribe to media
     * within the session.
     *
     * Authorization
     * - Query parameter moderator_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star or moderator:manage:guest_star
     *
     * URL
     * DELETE https://api.twitch.tv/helix/guest_star/slot
     *
     * @param AccessTokenInterface       $accessToken Requires OAuth Scope: channel:manage:guest_star or
     *                                               moderator:manage:guest_star
     */
    public function deleteGuestStarSlot(
        DeleteGuestStarSlotRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $query = array_filter(
            [
                'broadcaster_id'        => $request->broadcasterId,
                'moderator_id'          => $request->moderatorId,
                'session_id'            => $request->sessionId,
                'guest_id'              => $request->guestId,
                'slot_id'               => $request->slotId,
                'should_reinvite_guest' => $request->shouldReinviteGuest,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        $this->delete(self::BASE_PATH . '/slot', $accessToken, $query);
    }

    /**
     * (BETA) Allows a user to update slot settings for a particular guest within a Guest Star session, such as
     * allowing the user to share audio or video within the call as a host. These settings will be broadcasted to all
     * subscribers which control their view of the guest in that slot. One or more of the optional parameters to this
     * API can be specified at any time.
     *
     * Authorization
     * - Query parameter moderator_id must match the user_id in the User-Access token
     * - Requires OAuth Scope: channel:manage:guest_star or moderator:manage:guest_star
     *
     * URL
     * PATCH https://api.twitch.tv/helix/guest_star/slot_settings
     *
     * @param AccessTokenInterface               $accessToken Requires OAuth Scope: channel:manage:guest_star or
     *                                                        moderator:manage:guest_star
     */
    public function updateGuestStarSlotSettings(
        UpdateGuestStarSlotSettingsRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $query = array_filter(
            [
                'broadcaster_id'   => $request->broadcasterId,
                'moderator_id'     => $request->moderatorId,
                'session_id'       => $request->sessionId,
                'slot_id'          => $request->slotId,
                'is_audio_enabled' => $request->isAudioEnabled,
                'is_video_enabled' => $request->isVideoEnabled,
                'is_live'          => $request->isLive,
                'volume'           => $request->volume,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        $this->patchWithoutResponse(self::BASE_PATH . '/slot_settings', $accessToken, query: $query);
    }
}
