<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Chat\Request\GetChannelChatBadgesRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\GetChannelEmotesRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\GetChatSettingsRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\GetChattersRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\GetEmoteSetsRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\GetUserChatColorRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\SendChatAnnouncementRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\SendChatMessageRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\SendShoutoutRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\UpdateChatSettingsRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Request\UpdateUserChatColorRequest;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\ChannelChatBadgesResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\ChannelEmotesResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\ChatSettingsResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\ChattersResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\EmoteSetsResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\GlobalChatBadgesResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\GlobalEmotesResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\SendChatMessageResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\UserChatColorResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatColorEnum;

final class ChatApi extends AbstractApi
{
    private const string BASE_PATH = 'chat';

    /**
     * Gets the list of users that are connected to the broadcaster’s chat session.
     *
     * NOTE: There is a delay between when users join and leave a chat and when the list is updated accordingly.
     *
     * To determine whether a user is a moderator or VIP, use the Get Moderators and Get VIPs endpoints. You can check
     * the roles of up to 100 users.
     *
     * Authorization
     * Requires a user access token that includes the moderator:read:chatters scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/chatters
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the moderator:read:chatters
     *                                          scope.
     */
    public function getChatters(
        GetChattersRequest $request,
        AccessTokenInterface $accessToken,
    ): ChattersResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/chatters', ChattersResponse::class, $accessToken, $query);
    }

    /**
     * Gets the broadcaster’s list of custom emotes. Broadcasters create these custom emotes for users who subscribe to
     * or follow the channel or cheer Bits in the channel’s chat window. Learn More
     *
     * For information about the custom emotes, see subscriber emotes, Bits tier emotes, and follower emotes.
     *
     * NOTE: With the exception of custom follower emotes, users may use custom emotes in any Twitch chat.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/emotes
     *
     * @param AccessTokenInterface    $accessToken Requires an app access token or user access token.
     */
    public function getChannelEmotes(
        GetChannelEmotesRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelEmotesResponse {
        return $this->get(
            self::BASE_PATH . '/emotes',
            ChannelEmotesResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }

    /**
     * Gets the list of global emotes. Global emotes are Twitch-created emotes that users can use in any Twitch chat.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/emotes/global
     *
     * @see https://dev.twitch.tv/docs/irc/emotes Emotes
     *
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     */
    public function getGlobalEmotes(
        AccessTokenInterface $accessToken,
    ): GlobalEmotesResponse {
        return $this->get(self::BASE_PATH . '/emotes/global', GlobalEmotesResponse::class, $accessToken);
    }

    /**
     * Gets emotes for one or more specified emote sets.
     *
     * An emote set groups emotes that have a similar context. For example, Twitch places all the subscriber emotes
     * that a broadcaster uploads for their channel in the same emote set.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/emotes/set
     *
     * @see https://dev.twitch.tv/docs/irc/emotes Emotes
     *
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     */
    public function getEmoteSets(
        GetEmoteSetsRequest $request,
        AccessTokenInterface $accessToken,
    ): EmoteSetsResponse {
        return $this->get(
            self::BASE_PATH . '/emotes/set',
            EmoteSetsResponse::class,
            $accessToken,
            [
                'emote_set_id' => $request->emoteSetIds,
            ],
        );
    }

    /**
     * Gets the broadcaster’s list of custom chat badges. The list is empty if the broadcaster hasn’t created custom
     * chat badges. For information about custom badges, see subscriber badges and Bits badges.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/badges
     *
     * @param AccessTokenInterface        $accessToken Requires an app access token or user access token.
     */
    public function getChannelChatBadges(
        GetChannelChatBadgesRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelChatBadgesResponse {
        return $this->get(
            self::BASE_PATH . '/badges',
            ChannelChatBadgesResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }

    /**
     * Gets Twitch’s list of chat badges, which users may use in any channel’s chat room. For information about chat
     * badges, see Twitch Chat Badges Guide.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/badges/global
     *
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     */
    public function getGlobalChatBadges(
        AccessTokenInterface $accessToken,
    ): GlobalChatBadgesResponse {
        return $this->get(self::BASE_PATH . '/badges/global', GlobalChatBadgesResponse::class, $accessToken);
    }

    /**
     * Gets the broadcaster’s chat settings.
     *
     * For an overview of chat settings, see Chat Commands for Broadcasters and Moderators and Moderator Preferences.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/settings
     *
     * @param AccessTokenInterface   $accessToken Requires an app access token or user access token.
     */
    public function getChatSettings(
        GetChatSettingsRequest $request,
        AccessTokenInterface $accessToken,
    ): ChatSettingsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/settings', ChatSettingsResponse::class, $accessToken, $query);
    }

    /**
     * Updates the broadcaster’s chat settings.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:chat_settings scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/chat/settings
     *
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               moderator:manage:chat_settings scope.
     */
    public function updateChatSettings(
        UpdateChatSettingsRequest $request,
        AccessTokenInterface $accessToken,
    ): ChatSettingsResponse {
        return $this->patch(
            self::BASE_PATH . '/settings',
            ChatSettingsResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->settings),
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Sends an announcement to the broadcaster’s chat room.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:announcements scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/chat/announcements
     *
     * @param AccessTokenInterface        $accessToken Requires a user access token that includes the
     *                                                 moderator:manage:announcements scope.
     */
    public function sendChatAnnouncement(
        SendChatAnnouncementRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/announcements',
            $accessToken,
            $this->normalizer->normalize($request->announcement),
            [
                'broadcaster_id' => $request->broadcasterId,
                'moderator_id'   => $request->moderatorId,
            ],
        );
    }

    /**
     * Sends a Shoutout to the specified broadcaster. Typically, you send Shoutouts when you or one of your moderators
     * notice another broadcaster in your chat, the other broadcaster is coming up in conversation, or after they raid
     * your broadcast.
     *
     * Twitch’s Shoutout feature is a great way for you to show support for other broadcasters and help them grow.
     * Viewers who do not follow the other broadcaster will see a pop-up Follow button in your chat that they can click
     * to follow the other broadcaster. Learn More
     *
     * Rate Limits The broadcaster may send a Shoutout once every 2 minutes. They may send the same broadcaster a
     * Shoutout once every 60 minutes.
     *
     * To receive notifications when a Shoutout is sent or received, subscribe to the channel.shoutout.create and
     * channel.shoutout.receive subscription types. The channel.shoutout.create event includes cooldown periods that
     * indicate when the broadcaster may send another Shoutout without exceeding the endpoint’s rate limit.
     *
     * Authorization
     * Requires a user access token that includes the moderator:manage:shoutouts scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/chat/shoutouts
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the
     *                                          moderator:manage:shoutouts scope.
     */
    public function sendShoutout(
        SendShoutoutRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/shoutouts',
            $accessToken,
            query: [
                'from_broadcaster_id' => $request->fromBroadcasterId,
                'to_broadcaster_id'   => $request->toBroadcasterId,
                'moderator_id'        => $request->moderatorId,
            ],
        );
    }

    /**
     * Gets the color used for the user’s name in chat.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/chat/color
     *
     * @param AccessTokenInterface    $accessToken Requires an app access token or user access token.
     */
    public function getUserChatColor(
        GetUserChatColorRequest $request,
        AccessTokenInterface $accessToken,
    ): UserChatColorResponse {
        return $this->get(
            self::BASE_PATH . '/color',
            UserChatColorResponse::class,
            $accessToken,
            [
                'user_id' => $request->userIds,
            ],
        );
    }

    /**
     * Updates the color used for the user’s name in chat.
     *
     * Authorization
     * Requires a user access token that includes the user:manage:chat_color scope.
     *
     * URL
     * PUT https://api.twitch.tv/helix/chat/color
     *
     * @param AccessTokenInterface       $accessToken Requires a user access token that includes the
     *                                                user:manage:chat_color scope.
     */
    public function updateUserChatColor(
        UpdateUserChatColorRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $color = $request->color instanceof ChatColorEnum ? $request->color->value : $request->color;

        $this->putWithoutResponse(
            self::BASE_PATH . '/color',
            $accessToken,
            query: [
                'user_id' => $request->userId,
                'color'   => $color,
            ],
        );
    }

    /**
     * Sends a message to the broadcaster’s chat room.
     *
     * Authorization
     * Requires an app access token or user access token that includes the user:write:chat scope. If app access token
     * used, then additionally requires user:bot scope from chatting user, and either channel:bot scope from
     * broadcaster or moderator status.
     *
     * URL
     * POST https://api.twitch.tv/helix/chat/messages
     *
     * @param AccessTokenInterface   $accessToken Requires an app access token or user access token that includes the
     *                                            user:write:chat scope.
     */
    public function sendChatMessage(
        SendChatMessageRequest $request,
        AccessTokenInterface $accessToken,
    ): SendChatMessageResponse {
        $body = array_filter(
            [
                'broadcaster_id'          => $request->broadcasterId,
                'sender_id'               => $request->senderId,
                'message'                 => $request->message,
                'reply_parent_message_id' => $request->replyParentMessageId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->post(self::BASE_PATH . '/messages', SendChatMessageResponse::class, $accessToken, $body);
    }
}
