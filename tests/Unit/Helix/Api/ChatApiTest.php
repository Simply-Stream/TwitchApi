<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
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
use SimplyStream\TwitchApi\Helix\Api\ChatApi;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatColorEnum;
use SimplyStream\TwitchApi\Helix\Models\Chat\SendChatAnnouncement;
use SimplyStream\TwitchApi\Helix\Models\Chat\UpdateChatSettings;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ChatApi::class)]
final class ChatApiTest extends TestCase
{
    private ApiClientInterface $apiClient;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;
    private StaticAccessToken $token;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->token = new StaticAccessToken();
    }

    private function api(): ChatApi
    {
        return new ChatApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_chatters_omits_null_parameters(): void
    {
        $expected = new ChattersResponse(data: [], total: 0);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/chatters', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'first'          => 100,
            ])
            ->willReturn(['data' => [], 'total' => 0]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChatters(
                new GetChattersRequest(broadcasterId: '1234', moderatorId: 'mod-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_channel_emotes_forwards_broadcaster_id(): void
    {
        $expected = new ChannelEmotesResponse(data: [], template: 'tpl');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/emotes', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn(['data' => [], 'template' => 'tpl']);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelEmotes(new GetChannelEmotesRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_global_emotes_sends_no_query(): void
    {
        $expected = new GlobalEmotesResponse(data: [], template: 'tpl');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/emotes/global', $this->token, [])
            ->willReturn(['data' => [], 'template' => 'tpl']);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame($expected, $this->api()->getGlobalEmotes($this->token));
    }

    #[Test]
    public function get_emote_sets_repeats_emote_set_ids(): void
    {
        $expected = new EmoteSetsResponse(data: [], template: 'tpl');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/emotes/set', $this->token, ['emote_set_id' => ['s1', 's2']])
            ->willReturn(['data' => [], 'template' => 'tpl']);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getEmoteSets(new GetEmoteSetsRequest(emoteSetIds: ['s1', 's2']), $this->token),
        );
    }

    #[Test]
    public function get_channel_chat_badges_forwards_broadcaster_id(): void
    {
        $expected = new ChannelChatBadgesResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/badges', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelChatBadges(new GetChannelChatBadgesRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_global_chat_badges_sends_no_query(): void
    {
        $expected = new GlobalChatBadgesResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/badges/global', $this->token, [])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame($expected, $this->api()->getGlobalChatBadges($this->token));
    }

    #[Test]
    public function get_chat_settings_omits_null_moderator_id(): void
    {
        $expected = new ChatSettingsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/settings', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChatSettings(new GetChatSettingsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function update_chat_settings_patches_normalized_settings(): void
    {
        $settings = new UpdateChatSettings();
        $normalized = ['slow_mode' => true, 'slow_mode_wait_time' => 10];
        $expected = new ChatSettingsResponse(data: []);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($settings)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'chat/settings', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ], $normalized)
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateChatSettings(
                new UpdateChatSettingsRequest(broadcasterId: '1234', moderatorId: 'mod-1', settings: $settings),
                $this->token,
            ),
        );
    }

    #[Test]
    public function send_chat_announcement_posts_normalized_body_without_response(): void
    {
        $announcement = new SendChatAnnouncement(message: 'Hello');
        $normalized = ['message' => 'Hello', 'color' => 'primary'];

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($announcement)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'chat/announcements', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ], $normalized)
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->sendChatAnnouncement(
            new SendChatAnnouncementRequest(broadcasterId: '1234', moderatorId: 'mod-1', announcement: $announcement),
            $this->token,
        );
    }

    #[Test]
    public function send_shoutout_posts_query_only_without_body_or_normalizer(): void
    {
        $this->normalizer->expects($this->never())->method('normalize');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'chat/shoutouts', $this->token, [
                'from_broadcaster_id' => 'from-1',
                'to_broadcaster_id'   => 'to-1',
                'moderator_id'        => 'mod-1',
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->sendShoutout(
            new SendShoutoutRequest(fromBroadcasterId: 'from-1', toBroadcasterId: 'to-1', moderatorId: 'mod-1'),
            $this->token,
        );
    }

    #[Test]
    public function get_user_chat_color_repeats_user_ids(): void
    {
        $expected = new UserChatColorResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'chat/color', $this->token, ['user_id' => ['u1', 'u2']])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getUserChatColor(new GetUserChatColorRequest(userIds: ['u1', 'u2']), $this->token),
        );
    }

    #[Test]
    public function update_user_chat_color_unwraps_the_enum(): void
    {
        $color = ChatColorEnum::cases()[0];

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'chat/color', $this->token, [
                'user_id' => 'user-1',
                'color'   => $color->value,
            ], [])
            ->willReturn([]);

        $this->api()->updateUserChatColor(
            new UpdateUserChatColorRequest(userId: 'user-1', color: $color),
            $this->token,
        );
    }

    #[Test]
    public function update_user_chat_color_passes_hex_string_untouched(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'chat/color', $this->token, [
                'user_id' => 'user-1',
                'color'   => '#9146FF',
            ], [])
            ->willReturn([]);

        $this->api()->updateUserChatColor(
            new UpdateUserChatColorRequest(userId: 'user-1', color: '#9146FF'),
            $this->token,
        );
    }

    #[Test]
    public function send_chat_message_maps_body_manually_and_omits_null_reply(): void
    {
        $expected = new SendChatMessageResponse(data: []);

        $this->normalizer->expects($this->never())->method('normalize');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'chat/messages', $this->token, [], [
                'broadcaster_id' => '1234',
                'sender_id'      => 'sender-1',
                'message'        => 'Hello chat',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->sendChatMessage(
                new SendChatMessageRequest(broadcasterId: '1234', senderId: 'sender-1', message: 'Hello chat'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function send_chat_message_includes_reply_parent_when_set(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'chat/messages', $this->token, [], [
                'broadcaster_id'          => '1234',
                'sender_id'               => 'sender-1',
                'message'                 => 'Reply',
                'reply_parent_message_id' => 'parent-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new SendChatMessageResponse(data: []));

        $this->api()->sendChatMessage(
            new SendChatMessageRequest(
                broadcasterId: '1234',
                senderId: 'sender-1',
                message: 'Reply',
                replyParentMessageId: 'parent-1',
            ),
            $this->token,
        );
    }
}
