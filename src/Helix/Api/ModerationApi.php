<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\{AddBlockedTermRequest,
    AddChannelModeratorRequest,
    AddChannelVipRequest,
    BanUserRequest,
    CheckAutoModStatusRequest,
    DeleteChatMessagesRequest,
    GetAutoModSettingsRequest,
    GetBannedUsersRequest,
    GetBlockedTermsRequest,
    GetModeratedChannelsRequest,
    GetModeratorsRequest,
    GetShieldModeStatusRequest,
    GetVipsRequest,
    ManageHeldAutoModMessageRequest,
    RemoveBlockedTermRequest,
    RemoveChannelModeratorRequest,
    RemoveChannelVipRequest,
    UnbanUserRequest,
    UpdateAutoModSettingsRequest,
    UpdateShieldModeStatusRequest};
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\{AutoModSettingsResponse,
    AutoModStatusResponse,
    BannedUsersResponse,
    BanUserResponse,
    BlockedTermsResponse,
    ModeratedChannelsResponse,
    ModeratorsResponse,
    ShieldModeStatusResponse,
    VipsResponse};
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class ModerationApi extends AbstractApi
{
    private const string BASE_PATH = 'moderation';

    /**
     * Checks whether AutoMod would flag the specified message for review.
     *
     * AutoMod is a moderation tool that holds inappropriate or harassing chat messages for moderators to review.
     * Moderators approve or deny the messages that AutoMod flags; only approved messages are released to chat. AutoMod
     * detects misspellings and evasive language automatically. For information about AutoMod, see How to Use AutoMod.
     *
     * Rate Limits: Rates are limited per channel based on the account type rather than per access token.
     *
     * Account type | Limit per minute | Limit per hour
     * -------------|------------------|---------------
     * Normal       | 5                | 50
     * -------------|------------------|---------------
     * Affiliate    | 10               | 100
     * -------------|------------------|---------------
     * Partner      | 30               | 300
     * ------------------------------------------------
     * The above limits are in addition to the standard Twitch API rate limits. The rate limit headers in the response
     * represent the Twitch rate limits and not the above limits.
     *
     * Authorization
     * Requires a user access token that includes the moderation:read scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/moderation/enforcements/status
     *
     * @param CheckAutoModStatusRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the moderation:read
     *                                               scope.
     *
     * @return AutoModStatusResponse
     */
    public function checkAutoModStatus(
        CheckAutoModStatusRequest $request,
        AccessTokenInterface $accessToken,
    ): AutoModStatusResponse {
        return $this->post(
            self::BASE_PATH . '/enforcements/status',
            AutoModStatusResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->status),
            ['broadcaster_id' => $request->broadcasterId],
        );
    }

    /**
     * Allow or deny the message that AutoMod flagged for review. For information about AutoMod, see How to Use AutoMod.
     *
     * To get messages that AutoMod is holding for review, subscribe to the automod-queue.<moderator_id>.<channel_id>
     * topic using PubSub. PubSub sends a notification to your app when AutoMod holds a message for review.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:automod scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/moderation/automod/message
     *
     * @param ManageHeldAutoModMessageRequest $request
     * @param AccessTokenInterface            $accessToken Requires a user access token that includes the
     *                                                     moderator:manage:automod scope.
     *
     * @return void
     */
    public function manageHeldAutoModMessages(
        ManageHeldAutoModMessageRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/automod/message',
            $accessToken,
            $this->normalizer->normalize($request->message),
        );
    }

    /**
     * Gets the broadcaster’s AutoMod settings. The settings are used to automatically block inappropriate or harassing
     * messages from appearing in the broadcaster’s chat room.
     *
     * Authorization
     * Requires a user access token that includes the moderator:read:automod_settings scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/moderation/automod/settings
     *
     * @param GetAutoModSettingsRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               moderator:read:automod_settings scope.
     *
     * @return AutoModSettingsResponse
     */
    public function getAutoModSettings(
        GetAutoModSettingsRequest $request,
        AccessTokenInterface $accessToken,
    ): AutoModSettingsResponse {
        return $this->get(
            self::BASE_PATH . '/automod/settings',
            AutoModSettingsResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Updates the broadcaster’s AutoMod settings. The settings are used to automatically block inappropriate or
     * harassing messages from appearing in the broadcaster’s chat room.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:automod_settings scope.
     *
     * URL
     * PUT https://api.twitch.tv/helix/moderation/automod/settings
     *
     * @param UpdateAutoModSettingsRequest $request
     * @param AccessTokenInterface         $accessToken Requires a user access token that includes the
     *                                                  moderator:manage:automod_settings scope.
     *
     * @return AutoModSettingsResponse
     */
    public function updateAutoModSettings(
        UpdateAutoModSettingsRequest $request,
        AccessTokenInterface $accessToken,
    ): AutoModSettingsResponse {
        return $this->put(
            self::BASE_PATH . '/automod/settings',
            AutoModSettingsResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->settings),
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Gets all users that the broadcaster banned or put in a timeout.
     *
     * Authorization
     * Requires a user access token that includes the moderation:read or moderator:manage:banned_users scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/moderation/banned
     *
     * @param GetBannedUsersRequest $request
     * @param AccessTokenInterface  $accessToken Requires a user access token that includes the moderation:read or
     *                                           moderator:manage:banned_users scope.
     *
     * @return BannedUsersResponse
     */
    public function getBannedUsers(
        GetBannedUsersRequest $request,
        AccessTokenInterface $accessToken,
    ): BannedUsersResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userIds,
                'first'          => $request->first,
                'after'          => $request->after,
                'before'         => $request->before,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH . '/banned', BannedUsersResponse::class, $accessToken, $query);
    }

    /**
     * Bans a user from participating in the specified broadcaster’s chat room or puts them in a timeout.
     *
     * For information about banning or putting users in a timeout, see Ban a User and Timeout a User.
     *
     * If the user is currently in a timeout, you can call this endpoint to change the duration of the timeout or ban
     * them altogether. If the user is currently banned, you cannot call this method to put them in a timeout instead.
     *
     * To remove a ban or end a timeout, see Unban user.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:banned_users scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/moderation/bans
     *
     * @param BanUserRequest       $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the
     *                                          moderator:manage:banned_users scope.
     *
     * @return BanUserResponse
     */
    public function banUser(
        BanUserRequest $request,
        AccessTokenInterface $accessToken,
    ): BanUserResponse {
        return $this->post(
            self::BASE_PATH . '/bans',
            BanUserResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->ban),
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Removes the ban or timeout that was placed on the specified user.
     *
     * To ban a user, see Ban user.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:banned_users scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/moderation/bans
     *
     * @param UnbanUserRequest     $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the
     *                                          moderator:manage:banned_users scope.
     *
     * @return void
     */
    public function unbanUser(
        UnbanUserRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH . '/bans',
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'user_id'        => $request->userId,
            ],
        );
    }

    /**
     * Gets the broadcaster’s list of non-private, blocked words or phrases. These are the terms that the broadcaster
     * or moderator added manually or that were denied by AutoMod.
     *
     * Authorization
     * Requires a user access token that includes the moderator:read:blocked_terms or moderator:manage:blocked_terms
     * scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/moderation/blocked_terms
     *
     * @param GetBlockedTermsRequest $request
     * @param AccessTokenInterface   $accessToken Requires a user access token that includes the
     *                                            moderator:read:blocked_terms or moderator:manage:blocked_terms scope.
     *
     * @return BlockedTermsResponse
     */
    public function getBlockedTerms(
        GetBlockedTermsRequest $request,
        AccessTokenInterface $accessToken,
    ): BlockedTermsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/blocked_terms', BlockedTermsResponse::class, $accessToken, $query);
    }

    /**
     * Adds a word or phrase to the broadcaster’s list of blocked terms. These are the terms that the broadcaster
     * doesn’t want used in their chat room.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:blocked_terms scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/moderation/blocked_terms
     *
     * @param AddBlockedTermRequest $request
     * @param AccessTokenInterface  $accessToken Requires a user access token that includes the
     *                                           moderator:manage:blocked_terms scope.
     *
     * @return BlockedTermsResponse
     */
    public function addBlockedTerm(
        AddBlockedTermRequest $request,
        AccessTokenInterface $accessToken,
    ): BlockedTermsResponse {
        return $this->post(
            self::BASE_PATH . '/blocked_terms',
            BlockedTermsResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->term),
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Removes the word or phrase from the broadcaster’s list of blocked terms.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:blocked_terms scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/moderation/blocked_terms
     *
     * @param RemoveBlockedTermRequest $request
     * @param AccessTokenInterface     $accessToken Requires a user access token that includes the
     *                                              moderator:manage:blocked_terms scope.
     *
     * @return void
     */
    public function removeBlockedTerm(
        RemoveBlockedTermRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH . '/blocked_terms',
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'id'             => $request->id,
            ],
        );
    }

    /**
     * Removes a single chat message or all chat messages from the broadcaster’s chat room.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:chat_messages scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/moderation/chat
     *
     * @param DeleteChatMessagesRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               moderator:manage:chat_messages scope.
     *
     * @return void
     */
    public function deleteChatMessages(
        DeleteChatMessagesRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'message_id'     => $request->messageId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        $this->delete(self::BASE_PATH . '/chat', $accessToken, $query);
    }

    /**
     * Gets all users allowed to moderate the broadcaster’s chat room.
     *
     * Authorization
     * Requires a user access token that includes the moderation:read scope. If your app also adds and removes
     * moderators, you can use the channel:manage:moderators scope instead.
     *
     * URL
     * GET https://api.twitch.tv/helix/moderation/moderators
     *
     * @param GetModeratorsRequest $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the moderation:read scope. If
     *                                          your app also adds and removes moderators, you can use the
     *                                          channel:manage:moderators scope instead.
     *
     * @return ModeratorsResponse
     */
    public function getModerators(
        GetModeratorsRequest $request,
        AccessTokenInterface $accessToken,
    ): ModeratorsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userIds,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH . '/moderators', ModeratorsResponse::class, $accessToken, $query);
    }

    /**
     * Adds a moderator to the broadcaster’s chat room.
     *
     * Rate Limits: The broadcaster may add a maximum of 10 moderators within a 10-second window.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:moderators scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/moderation/moderators
     *
     * @param AddChannelModeratorRequest $request
     * @param AccessTokenInterface       $accessToken Requires a user access token that includes the
     *                                                channel:manage:moderators scope.
     *
     * @return void
     */
    public function addChannelModerator(
        AddChannelModeratorRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/moderators',
            $accessToken,
            query: [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userId,
            ],
        );
    }

    /**
     * Removes a moderator from the broadcaster’s chat room.
     *
     * Rate Limits: The broadcaster may remove a maximum of 10 moderators within a 10-second window.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:moderators scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/moderation/moderators
     *
     * @param RemoveChannelModeratorRequest $request
     * @param AccessTokenInterface          $accessToken Requires a user access token that includes the
     *                                                   channel:manage:moderators scope.
     *
     * @return void
     */
    public function removeChannelModerator(
        RemoveChannelModeratorRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH . '/moderators',
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userId,
            ],
        );
    }

    /**
     * Gets a list of the broadcaster’s VIPs.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:vips scope. If your app also adds and removes VIP
     * status, you can use the channel:manage:vips scope instead.
     *
     * URL
     * GET https://api.twitch.tv/helix/channels/vips
     *
     * @param GetVipsRequest       $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:read:vips scope.
     *                                          If your app also adds and removes VIP status, you can use the
     *                                          channel:manage:vips scope instead.
     *
     * @return VipsResponse
     */
    public function getVips(
        GetVipsRequest $request,
        AccessTokenInterface $accessToken,
    ): VipsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userIds,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get('channels/vips', VipsResponse::class, $accessToken, $query);
    }

    /**
     * Adds the specified user as a VIP in the broadcaster’s channel.
     *
     * Rate Limits: The broadcaster may add a maximum of 10 VIPs within a 10-second window.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:vips scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/channels/vips
     *
     * @param AddChannelVipRequest $request
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:vips
     *                                          scope.
     *
     * @return void
     */
    public function addChannelVip(
        AddChannelVipRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            'channels/vips',
            $accessToken,
            query: [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userId,
            ],
        );
    }

    /**
     * Removes the specified user as a VIP in the broadcaster’s channel.
     *
     * If the broadcaster is removing the user’s VIP status, the ID in the broadcaster_id query parameter must match
     * the user ID in the access token; otherwise, if the user is removing their VIP status themselves, the ID in the
     * user_id query parameter must match the user ID in the access token.
     *
     * Rate Limits: The broadcaster may remove a maximum of 10 VIPs within a 10-second window.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:vips scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/channels/vips
     *
     * @param RemoveChannelVipRequest $request
     * @param AccessTokenInterface    $accessToken Requires a user access token that includes the channel:manage:vips
     *                                             scope.
     *
     * @return void
     */
    public function removeChannelVip(
        RemoveChannelVipRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            'channels/vips',
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userId,
            ],
        );
    }

    /**
     * Activates or deactivates the broadcaster’s Shield Mode.
     *
     * Twitch’s Shield Mode feature is like a panic button that broadcasters can push to protect themselves from chat
     * abuse coming from one or more accounts. When activated, Shield Mode applies the overrides that the broadcaster
     * configured in the Twitch UX. If the broadcaster hasn’t configured Shield Mode, it applies default overrides.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:shield_mode scope.
     *
     * URL
     * PUT https://api.twitch.tv/helix/moderation/shield_mode
     *
     * @param UpdateShieldModeStatusRequest $request
     * @param AccessTokenInterface          $accessToken Requires a user access token that includes the
     *                                                   moderator:manage:shield_mode scope.
     *
     * @return ShieldModeStatusResponse
     */
    public function updateShieldModeStatus(
        UpdateShieldModeStatusRequest $request,
        AccessTokenInterface $accessToken,
    ): ShieldModeStatusResponse {
        return $this->put(
            self::BASE_PATH . '/shield_mode',
            ShieldModeStatusResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->status),
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Gets the broadcaster’s Shield Mode activation status.
     *
     * To receive notification when the broadcaster activates and deactivates Shield Mode, subscribe to the
     * channel.shield_mode.begin and channel.shield_mode.end subscription types.
     *
     * Authorization
     * Requires a user access token that includes the moderator:read:shield_mode or moderator:manage:shield_mode scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/moderation/shield_mode
     *
     * @param GetShieldModeStatusRequest $request
     * @param AccessTokenInterface       $accessToken Requires a user access token that includes the
     *                                               moderator:read:shield_mode or moderator:manage:shield_mode scope.
     *
     * @return ShieldModeStatusResponse
     */
    public function getShieldModeStatus(
        GetShieldModeStatusRequest $request,
        AccessTokenInterface $accessToken,
    ): ShieldModeStatusResponse {
        return $this->get(
            self::BASE_PATH . '/shield_mode',
            ShieldModeStatusResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Gets a list of channels that the specified user has moderator privileges in.
     *
     * Authorization
     * - Query parameter user_id must match the user ID in the User-Access token
     * - Requires OAuth Scope: user:read:moderated_channels
     *
     * URL
     * GET https://api.twitch.tv/helix/moderation/channels
     *
     * @param GetModeratedChannelsRequest $request
     * @param AccessTokenInterface        $accessToken Requires OAuth Scope: user:read:moderated_channels
     *
     * @return ModeratedChannelsResponse
     */
    public function getModeratedChannels(
        GetModeratedChannelsRequest $request,
        AccessTokenInterface $accessToken,
    ): ModeratedChannelsResponse {
        $query = array_filter(
            [
                'user_id' => $request->userId,
                'after'   => $request->after,
                'first'   => $request->first,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/channels', ModeratedChannelsResponse::class, $accessToken, $query);
    }
}
