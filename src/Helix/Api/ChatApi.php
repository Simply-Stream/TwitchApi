<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChannelEmote;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatBadge;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatColorEnum;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatSettings;
use SimplyStream\TwitchApi\Helix\Models\Chat\Chatter;
use SimplyStream\TwitchApi\Helix\Models\Chat\EmoteSet;
use SimplyStream\TwitchApi\Helix\Models\Chat\GlobalEmote;
use SimplyStream\TwitchApi\Helix\Models\Chat\Message;
use SimplyStream\TwitchApi\Helix\Models\Chat\SendChatAnnouncementRequest;
use SimplyStream\TwitchApi\Helix\Models\Chat\SendChatMessageRequest;
use SimplyStream\TwitchApi\Helix\Models\Chat\UpdateChatSettingsRequest;
use SimplyStream\TwitchApi\Helix\Models\Chat\UserChatColor;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchResponseInterface;
use SimplyStream\TwitchApi\Helix\Models\TwitchTemplatedDataResponse;

class ChatApi extends AbstractApi
{
    protected const BASE_PATH = 'chat';

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
     * @param string               $broadcasterId The ID of the broadcaster whose list of chatters you want to get.
     * @param string               $moderatorId   The ID of the broadcaster or one of the broadcaster’s moderators.
     *                                            This ID must match the user ID in the user access token.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the
     *                                            moderator:read:chatters scope.
     * @param int                  $first         The maximum number of items to return per page in the response. The
     *                                            minimum page size is
     *                                            1 item per page and the maximum is 1,000. The default is 100.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<Chatter[]>
     * @throws JsonException
     */
    public function getChatters(
        string $broadcasterId,
        string $moderatorId,
        AccessTokenInterface $accessToken,
        int $first = 100,
        string $after = null
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/chatters',
            query: [
                'broadcaster_id' => $broadcasterId,
                'moderator_id' => $moderatorId,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, Chatter::class),
            accessToken: $accessToken
        );
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
     * @param string               $broadcasterId      An ID that identifies the broadcaster whose emotes you want to
     *                                                 get.
     * @param AccessTokenInterface $accessToken        Requires an app access token or user access token.
     *
     * @return TwitchTemplatedDataResponse<ChannelEmote[]>
     */
    public function getChannelEmotes(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchResponseInterface {
        return $this->sendRequest(
            path: self::BASE_PATH . '/emotes',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchTemplatedDataResponse::class, ChannelEmote::class),
            accessToken: $accessToken
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
     *
     * @return TwitchTemplatedDataResponse<GlobalEmote[]>
     */
    public function getGlobalEmotes(
        AccessTokenInterface $accessToken
    ): TwitchTemplatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/emotes/global',
            type: sprintf('%s<%s[]>', TwitchTemplatedDataResponse::class, GlobalEmote::class),
            accessToken: $accessToken
        );
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
     * @param string               $emoteSetId      An ID that identifies the emote set to get. Include this parameter
     *                                              for each emote set you want to get. For example,
     *                                              emote_set_id=1234&emote_set_id=5678. You may specify a maximum of
     *                                              25 IDs. The response contains only the IDs that were found and
     *                                              ignores duplicate IDs.
     *
     *                                              To get emote set IDs, use the Get Channel Emotes API.
     * @param AccessTokenInterface $accessToken     Requires an app access token or user access token.
     *
     * @return TwitchTemplatedDataResponse<EmoteSet[]>
     */
    public function getEmoteSets(
        string $emoteSetId,
        AccessTokenInterface $accessToken
    ): TwitchTemplatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/emotes/set',
            query: [
                'emote_set_id' => $emoteSetId,
            ],
            type: sprintf('%s<%s[]>', TwitchTemplatedDataResponse::class, EmoteSet::class),
            accessToken: $accessToken
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
     * @param string               $broadcasterId The ID of the broadcaster whose chat badges you want to get.
     * @param AccessTokenInterface $accessToken   Requires an app access token or user access token.
     *
     * @return TwitchDataResponse<ChatBadge[]>
     */
    public function getChannelChatBadges(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchResponseInterface {
        return $this->sendRequest(
            path: self::BASE_PATH . '/badges',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ChatBadge::class),
            accessToken: $accessToken
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
     *
     * @return TwitchDataResponse<ChatBadge[]>
     */
    public function getGlobalChatBadges(
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/badges/global',
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ChatBadge::class),
            accessToken: $accessToken
        );
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
     * @param string               $broadcasterId      The ID of the broadcaster whose chat settings you want to get.
     * @param AccessTokenInterface $accessToken        Requires an app access token or user access token.
     * @param string|null          $moderatorId        The ID of a user that has permission to moderate the
     *                                                 broadcaster’s chat room, or the broadcaster’s ID if they’re
     *                                                 getting the settings.
     *
     *                                                 This field is required only if you want to include the
     *                                                 non_moderator_chat_delay and non_moderator_chat_delay_duration
     *                                                 settings in the response.
     *
     *                                                 If you specify this field, this ID must match the user ID in the
     *                                                 user access token.
     *
     * @return TwitchDataResponse<ChatSettings[]>
     */
    public function getChatSettings(
        string $broadcasterId,
        AccessTokenInterface $accessToken,
        string $moderatorId = null,
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/settings',
            query: [
                'broadcaster_id' => $broadcasterId,
                'moderator_id' => $moderatorId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ChatSettings::class),
            accessToken: $accessToken
        );
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
     * @param string                    $broadcasterId The ID of the broadcaster whose chat settings you want to
     *                                                 update.
     * @param string                    $moderatorId   The ID of a user that has permission to moderate the
     *                                                 broadcaster’s chat room, or the broadcaster’s ID if they’re
     *                                                 making the update. This ID must match the user ID in the user
     *                                                 access token.
     * @param UpdateChatSettingsRequest $body
     * @param AccessTokenInterface      $accessToken   Requires a user access token that includes the
     *                                                 moderator:manage:chat_settings scope.
     *
     * @return TwitchDataResponse<ChatSettings[]>
     */
    public function updateChatSettings(
        string $broadcasterId,
        string $moderatorId,
        UpdateChatSettingsRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/settings',
            query: [
                'broadcaster_id' => $broadcasterId,
                'moderator_id' => $moderatorId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ChatSettings::class),
            method: 'PATCH',
            body: $body,
            accessToken: $accessToken
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
     * @param string                      $broadcasterId The ID of the broadcaster that owns the chat room to send the
     *                                                   announcement to.
     * @param string                      $moderatorId   The ID of a user who has permission to moderate the
     *                                                   broadcaster’s chat room, or the broadcaster’s ID if they’re
     *                                                   sending the announcement. This ID must match the user ID in
     *                                                   the user access token.
     * @param SendChatAnnouncementRequest $body
     * @param AccessTokenInterface        $accessToken   Requires a user access token that includes the
     *                                                   moderator:manage:announcements scope.
     *
     * @return void
     */
    public function sendChatAnnouncement(
        string $broadcasterId,
        string $moderatorId,
        SendChatAnnouncementRequest $body,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/announcements',
            query: [
                'broadcaster_id' => $broadcasterId,
                'moderator_id' => $moderatorId,
            ],
            method: 'POST',
            body: $body,
            accessToken: $accessToken
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
     * @param string               $fromBroadcasterId The ID of the broadcaster that’s sending the Shoutout.
     * @param string               $toBroadcasterId   The ID of the broadcaster that’s receiving the Shoutout.
     * @param string               $moderatorId       The ID of the broadcaster or a user that is one of the
     *                                                broadcaster’s moderators. This ID must match the user ID in the
     *                                                access token.
     * @param AccessTokenInterface $accessToken       Requires a user access token that includes the
     *                                                moderator:manage:shoutouts scope.
     *
     * @return void
     */
    public function sendShoutout(
        string $fromBroadcasterId,
        string $toBroadcasterId,
        string $moderatorId,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/shoutouts',
            query: [
                'from_broadcaster_id' => $fromBroadcasterId,
                'to_broadcaster_id' => $toBroadcasterId,
                'moderator_id' => $moderatorId,
            ],
            method: 'POST',
            accessToken: $accessToken
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
     * @param string               $userId              The ID of the user whose username color you want to get. To
     *                                                  specify more than one user, include the user_id parameter for
     *                                                  each user to get. For example,
     *                                                  &user_id=1234&user_id=5678. The maximum number of IDs that you
     *                                                  may specify is 100.
     *
     *                                                  The API ignores duplicate IDs and IDs that weren’t found.
     * @param AccessTokenInterface $accessToken         Requires an app access token or user access token.
     *
     * @return TwitchDataResponse<UserChatColor[]>
     */
    public function getUserChatColor(
        string $userId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/color',
            query: [
                'user_id' => $userId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, UserChatColor::class),
            accessToken: $accessToken
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
     * @param string               $userId      The ID of the user whose chat color you want to update. This ID must
     *                                          match the user ID in the access token.
     * @param ChatColorEnum|string $color       The color to use for the user’s name in chat. All users may specify one
     *                                          of the following named color values.
     *                                          - blue
     *                                          - blue_violet
     *                                          - cadet_blue
     *                                          - chocolate
     *                                          - coral
     *                                          - dodger_blue
     *                                          - firebrick
     *                                          - golden_rod
     *                                          - green
     *                                          - hot_pink
     *                                          - orange_red
     *                                          - red
     *                                          - sea_green
     *                                          - spring_green
     *                                          - yellow_green
     *
     *                                          Turbo and Prime users may specify a named color or a Hex color code like
     *                                          #9146FF. If you use a Hex color code, remember to URL encode it.
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the user:manage:chat_color
     *                                          scope.
     *
     * @return void
     */
    public function updateUserChatColor(
        string $userId,
        ChatColorEnum|string $color,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/color',
            query: [
                'user_id' => $userId,
                'color' => $color instanceof ChatColorEnum ? $color->value : $color,
            ],
            method: 'PUT',
            accessToken: $accessToken
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
     * @param string               $broadcasterId           The ID of the broadcaster whose chat room the message will
     *                                                      be sent to.
     * @param string               $senderId                The ID of the user sending the message. This ID must match
     *                                                      the user ID in the user access token.
     * @param string               $message                 The message to send. The message is limited to a maximum of
     *                                                      500 characters. Chat messages can also include emoticons.
     *                                                      To include emoticons, use the name of the emote. The names
     *                                                      are case sensitive. Don’t include colons around the name
     *                                                      (e.g., :bleedPurple:). If Twitch recognizes the name,
     *                                                      Twitch converts the name to the emote before writing the
     *                                                      chat message to the chat room
     * @param AccessTokenInterface $accessToken
     * @param string|null          $replyParentMessageId    The ID of the chat message being replied to.
     *
     * @return TwitchDataResponse<Message[]>
     */
    public function sendChatMessage(
        string $broadcasterId,
        string $senderId,
        string $message,
        AccessTokenInterface $accessToken,
        ?string $replyParentMessageId = null
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/messages',
            body: new SendChatMessageRequest(
                $broadcasterId,
                $senderId,
                $message,
                $replyParentMessageId
            ),
            accessToken: $accessToken,
        );
    }
}
