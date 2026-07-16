<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
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
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\SendChatMessageResponse;
use SimplyStream\TwitchApi\Helix\Api\Chat\Response\UserChatColorResponse;
use SimplyStream\TwitchApi\Helix\Api\ChatApi;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChannelEmote;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatBadge;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatColorEnum;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatSettings;
use SimplyStream\TwitchApi\Helix\Models\Chat\Chatter;
use SimplyStream\TwitchApi\Helix\Models\Chat\DropReason;
use SimplyStream\TwitchApi\Helix\Models\Chat\EmoteSet;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;
use SimplyStream\TwitchApi\Helix\Models\Chat\Message;
use SimplyStream\TwitchApi\Helix\Models\Chat\SendChatAnnouncement;
use SimplyStream\TwitchApi\Helix\Models\Chat\UpdateChatSettings;
use SimplyStream\TwitchApi\Helix\Models\Chat\UserChatColor;
use SimplyStream\TwitchApi\Helix\Models\Chat\Version;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsChatApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ChatApi::class)]
final class ChatApiTest extends TestCase
{
    use BuildsChatApi;

    #[Test]
    public function get_chatters_denormalizes_the_total(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'    => '128393656',
                'user_login' => 'smittysmithers',
                'user_name'  => 'smittysmithers',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
            'total'      => 8,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChatters(
            new GetChattersRequest(broadcasterId: '123456', moderatorId: '654321'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=123456&moderator_id=654321&first=100',
            $http->getLastRequest()->getUri()->getQuery(),
        );

        $this->assertInstanceOf(ChattersResponse::class, $response);
        $this->assertSame(8, $response->total);
        $this->assertInstanceOf(Chatter::class, $response->data[0]);
        $this->assertSame('smittysmithers', $response->data[0]->userName);
    }

    #[Test]
    public function get_channel_emotes_denormalizes_the_image_object(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'     => '304456832',
                'name'   => 'twitchdevPitchfork',
                'images' => [
                    'url_1x' => 'https://static-cdn.jtvnw.net/emoticons/v2/304456832/static/light/1.0',
                    'url_2x' => 'https://static-cdn.jtvnw.net/emoticons/v2/304456832/static/light/2.0',
                    'url_4x' => 'https://static-cdn.jtvnw.net/emoticons/v2/304456832/static/light/3.0',
                ],
                'tier'         => '1000',
                'emote_type'   => 'subscriptions',
                'emote_set_id' => '301590448',
                'format'       => ['static'],
                'scale'        => ['1.0', '2.0', '3.0'],
                'theme_mode'   => ['light', 'dark'],
            ]],
            'template' => 'https://static-cdn.jtvnw.net/emoticons/v2/{{id}}/{{format}}/{{theme_mode}}/{{scale}}',
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelEmotes(
            new GetChannelEmotesRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(ChannelEmotesResponse::class, $response);

        $emote = $response->data[0];
        $this->assertInstanceOf(ChannelEmote::class, $emote);
        $this->assertSame('twitchdevPitchfork', $emote->name);
        $this->assertSame('1000', $emote->tier);
        $this->assertSame('subscriptions', $emote->emoteType);
        $this->assertSame(['static'], $emote->format);
        $this->assertSame(['light', 'dark'], $emote->themeMode);

        // Digit-suffixed keys: url_1x must land in $url1x.
        $this->assertInstanceOf(Image::class, $emote->images);
        $this->assertSame(
            'https://static-cdn.jtvnw.net/emoticons/v2/304456832/static/light/1.0',
            $emote->images->url1x,
        );
        $this->assertSame(
            'https://static-cdn.jtvnw.net/emoticons/v2/304456832/static/light/3.0',
            $emote->images->url4x,
        );
    }

    #[Test]
    public function get_emote_sets_repeats_the_set_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'     => '304456832',
                'name'   => 'twitchdevPitchfork',
                'images' => [
                    'url_1x' => 'https://example.com/1.0',
                    'url_2x' => 'https://example.com/2.0',
                    'url_4x' => 'https://example.com/3.0',
                ],
                'emote_type'   => 'subscriptions',
                'emote_set_id' => '301590448',
                'owner_id'     => '141981764',
                'format'       => ['static'],
                'scale'        => ['1.0'],
                'theme_mode'   => ['light'],
            ]],
            'template' => 'https://static-cdn.jtvnw.net/emoticons/v2/{{id}}/{{format}}/{{theme_mode}}/{{scale}}',
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getEmoteSets(
            new GetEmoteSetsRequest(emoteSetIds: ['301590448', '300374282']),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'emote_set_id=301590448&emote_set_id=300374282',
            $http->getLastRequest()->getUri()->getQuery(),
        );

        $this->assertInstanceOf(EmoteSetsResponse::class, $response);
        $emote = $response->data[0];
        $this->assertInstanceOf(EmoteSet::class, $emote);
        $this->assertSame('141981764', $emote->ownerId);
        $this->assertSame('301590448', $emote->emoteSetId);
    }

    #[Test]
    public function get_channel_chat_badges_denormalizes_the_version_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'set_id'   => 'bits',
                'versions' => [[
                    'id'           => '1',
                    'image_url_1x' => 'https://static-cdn.jtvnw.net/badges/v1/abc/1',
                    'image_url_2x' => 'https://static-cdn.jtvnw.net/badges/v1/abc/2',
                    'image_url_4x' => 'https://static-cdn.jtvnw.net/badges/v1/abc/3',
                    'title'        => 'cheer 1',
                    'description'  => 'cheer 1',
                    'click_action' => 'visit_url',
                    'click_url'    => 'https://bits.twitch.tv',
                ]],
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelChatBadges(
            new GetChannelChatBadgesRequest(broadcasterId: '135093069'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(ChannelChatBadgesResponse::class, $response);

        $badge = $response->data[0];
        $this->assertInstanceOf(ChatBadge::class, $badge);
        $this->assertSame('bits', $badge->setId);

        $version = $badge->versions[0];
        $this->assertInstanceOf(Version::class, $version);
        $this->assertSame('cheer 1', $version->title);
        $this->assertSame('visit_url', $version->clickAction);
        $this->assertSame('https://static-cdn.jtvnw.net/badges/v1/abc/1', $version->imageUrl1x);
    }

    #[Test]
    public function get_chat_settings_accepts_null_durations_when_modes_are_off(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'                   => '713936733',
                'emote_mode'                       => false,
                'follower_mode'                    => false,
                'follower_mode_duration'           => null,
                'slow_mode'                        => false,
                'slow_mode_wait_time'              => null,
                'subscriber_mode'                  => false,
                'unique_chat_mode'                 => false,
                'moderator_id'                     => '713936733',
                'non_moderator_chat_delay'         => false,
                'non_moderator_chat_delay_duration' => null,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChatSettings(
            new GetChatSettingsRequest(broadcasterId: '713936733', moderatorId: '713936733'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(ChatSettingsResponse::class, $response);

        $settings = $response->data[0];
        $this->assertInstanceOf(ChatSettings::class, $settings);
        $this->assertFalse($settings->followerMode);
        $this->assertNull($settings->followerModeDuration);
        $this->assertFalse($settings->slowMode);
        $this->assertNull($settings->slowModeWaitTime);
        $this->assertNull($settings->nonModeratorChatDelayDuration);
    }

    #[Test]
    public function update_chat_settings_sends_only_the_fields_that_were_set(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'         => '713936733',
                'emote_mode'             => false,
                'follower_mode'          => false,
                'follower_mode_duration' => null,
                'slow_mode'              => true,
                'slow_mode_wait_time'    => 10,
                'subscriber_mode'        => false,
                'unique_chat_mode'       => false,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateChatSettings(
            new UpdateChatSettingsRequest(
                broadcasterId: '713936733',
                moderatorId: '713936733',
                settings: new UpdateChatSettings(slowMode: true, slowModeWaitTime: 10),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame(
            'broadcaster_id=713936733&moderator_id=713936733',
            $request->getUri()->getQuery(),
        );

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['slow_mode' => true, 'slow_mode_wait_time' => 10], $body);
    }

    #[Test]
    public function send_chat_announcement_normalizes_the_announcement_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->sendChatAnnouncement(
            new SendChatAnnouncementRequest(
                broadcasterId: '11111',
                moderatorId: '44444',
                announcement: new SendChatAnnouncement(message: 'Hello chat!', color: 'purple'),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/chat/announcements', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=11111&moderator_id=44444', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['message' => 'Hello chat!', 'color' => 'purple'], $body);
    }

    #[Test]
    public function send_shoutout_sends_query_only_and_an_empty_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->sendShoutout(
            new SendShoutoutRequest(fromBroadcasterId: '12345', toBroadcasterId: '626262', moderatorId: '98765'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame(
            'from_broadcaster_id=12345&to_broadcaster_id=626262&moderator_id=98765',
            $request->getUri()->getQuery(),
        );
        $this->assertSame('[]', (string) $request->getBody());
    }

    #[Test]
    public function get_user_chat_color_repeats_the_user_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'    => '11111',
                'user_login' => 'spanishsahara',
                'user_name'  => 'spanishSahara',
                'color'      => '#9146FF',
            ], [
                'user_id'    => '44444',
                'user_login' => 'wisconsinpsycho',
                'user_name'  => 'WisconsinPsycho',
                'color'      => '',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getUserChatColor(
            new GetUserChatColorRequest(userIds: ['11111', '44444']),
            new StaticAccessToken(),
        );

        $this->assertSame('user_id=11111&user_id=44444', $http->getLastRequest()->getUri()->getQuery());

        $this->assertInstanceOf(UserChatColorResponse::class, $response);
        $this->assertInstanceOf(UserChatColor::class, $response->data[0]);
        $this->assertSame('#9146FF', $response->data[0]->color);

        // No color set means an empty string, not null.
        $this->assertSame('', $response->data[1]->color);
    }

    #[Test]
    public function update_user_chat_color_unwraps_the_color_enum(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->updateUserChatColor(
            new UpdateUserChatColorRequest(userId: '123', color: ChatColorEnum::BlueViolet),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'user_id=123&color=blue_violet',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function update_user_chat_color_accepts_a_raw_hex_string(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->updateUserChatColor(
            new UpdateUserChatColorRequest(userId: '123', color: '#9146FF'),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertSame('#9146FF', $query['color']);
    }

    #[Test]
    public function send_chat_message_omits_a_null_reply_parent_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'message_id'  => 'abc-123-def',
                'is_sent'     => true,
                'drop_reason' => null,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->sendChatMessage(
            new SendChatMessageRequest(broadcasterId: '12826', senderId: '141981764', message: 'Hello, world!'),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'broadcaster_id' => '12826',
            'sender_id'      => '141981764',
            'message'        => 'Hello, world!',
        ], $body);

        $this->assertInstanceOf(SendChatMessageResponse::class, $response);
        $message = $response->data[0];
        $this->assertInstanceOf(Message::class, $message);
        $this->assertTrue($message->isSent);
        $this->assertNull($message->dropReason);
    }

    #[Test]
    public function send_chat_message_denormalizes_a_drop_reason(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'message_id'  => '',
                'is_sent'     => false,
                'drop_reason' => [
                    'code'    => 'msg_rejected',
                    'message' => 'Your message is being checked by mods and has not been sent.',
                ],
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->sendChatMessage(
            new SendChatMessageRequest(
                broadcasterId: '12826',
                senderId: '141981764',
                message: 'Hello, world!',
                replyParentMessageId: 'parent-1',
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('parent-1', $body['reply_parent_message_id']);

        $message = $response->data[0];
        $this->assertFalse($message->isSent);

        // drop_reason is a single object, not a list.
        $this->assertInstanceOf(DropReason::class, $message->dropReason);
        $this->assertSame('msg_rejected', $message->dropReason->code);
    }
}
