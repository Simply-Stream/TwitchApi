<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\AddBlockedTermRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\AddChannelModeratorRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\AddChannelVipRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\BanUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\CheckAutoModStatusRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\DeleteChatMessagesRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetBannedUsersRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetModeratedChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetModeratorsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetVipsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\RemoveBlockedTermRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\RemoveChannelModeratorRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\RemoveChannelVipRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\UnbanUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\UpdateAutoModSettingsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\UpdateShieldModeStatusRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\AutoModSettingsResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\AutoModStatusResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\BannedUsersResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\BanUserResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\BlockedTermsResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\ModeratedChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\ModeratorsResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\ShieldModeStatusResponse;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Response\VipsResponse;
use SimplyStream\TwitchApi\Helix\Api\ModerationApi;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AddBlockedTerm;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModMessage;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BanUser;
use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateAutoModSettings;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateShieldModeStatus;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ModerationApi::class)]
final class ModerationApiTest extends TestCase
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

    private function api(): ModerationApi
    {
        return new ModerationApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function check_automod_status_posts_normalized_body_with_broadcaster_query(): void
    {
        $status = new CheckAutoModStatus([new AutoModMessage(msgId: '1', msgText: 'hi')]);
        $normalized = ['data' => [['msg_id' => '1', 'msg_text' => 'hi']]];
        $expected = new AutoModStatusResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($status)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'moderation/enforcements/status', $this->token, ['broadcaster_id' => '1234'], $normalized)
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->checkAutoModStatus(
                new CheckAutoModStatusRequest(broadcasterId: '1234', status: $status),
                $this->token,
            ),
        );
    }

    #[Test]
    public function update_automod_settings_puts_normalized_body(): void
    {
        $settings = new UpdateAutoModSettings(
            aggression: 0,
            bullying: 0,
            disability: 0,
            misogyny: 0,
            raceEthnicityOrReligion: 0,
            sexBasedTerms: 0,
            sexualitySexOrGender: 0,
            swearing: 0,
            overallLevel: 2,
        );
        $normalized = ['overall_level' => 2];
        $expected = new AutoModSettingsResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($settings)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'moderation/automod/settings', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ], $normalized)
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateAutoModSettings(
                new UpdateAutoModSettingsRequest(broadcasterId: '1234', moderatorId: 'mod-1', settings: $settings),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_banned_users_repeats_user_ids_and_omits_empty(): void
    {
        $expected = new BannedUsersResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'moderation/banned', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => ['u1', 'u2'],
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getBannedUsers(
                new GetBannedUsersRequest(broadcasterId: '1234', userIds: ['u1', 'u2']),
                $this->token,
            ),
        );
    }

    #[Test]
    public function ban_user_posts_normalized_body_with_id_query(): void
    {
        $ban = new BanUser(userId: 'target-1', duration: 600, reason: 'spam');
        $normalized = ['data' => ['user_id' => 'target-1', 'duration' => 600, 'reason' => 'spam']];
        $expected = new BanUserResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($ban)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'moderation/bans', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ], $normalized)
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->banUser(
                new BanUserRequest(broadcasterId: '1234', moderatorId: 'mod-1', ban: $ban),
                $this->token,
            ),
        );
    }

    #[Test]
    public function unban_user_deletes_query_without_response(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'moderation/bans', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'user_id'        => 'target-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->unbanUser(
            new UnbanUserRequest(broadcasterId: '1234', moderatorId: 'mod-1', userId: 'target-1'),
            $this->token,
        );
    }

    #[Test]
    public function add_blocked_term_posts_normalized_body(): void
    {
        $term = new AddBlockedTerm(text: 'badword');
        $normalized = ['text' => 'badword'];
        $expected = new BlockedTermsResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($term)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'moderation/blocked_terms', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ], $normalized)
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->addBlockedTerm(
                new AddBlockedTermRequest(broadcasterId: '1234', moderatorId: 'mod-1', term: $term),
                $this->token,
            ),
        );
    }

    #[Test]
    public function remove_blocked_term_deletes_query(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'moderation/blocked_terms', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'id'             => 'term-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->removeBlockedTerm(
            new RemoveBlockedTermRequest(broadcasterId: '1234', moderatorId: 'mod-1', id: 'term-1'),
            $this->token,
        );
    }

    #[Test]
    public function delete_chat_messages_omits_null_message_id(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'moderation/chat', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->deleteChatMessages(
            new DeleteChatMessagesRequest(broadcasterId: '1234', moderatorId: 'mod-1'),
            $this->token,
        );
    }

    #[Test]
    public function get_moderators_repeats_user_ids(): void
    {
        $expected = new ModeratorsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'moderation/moderators', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => ['u1'],
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getModerators(
                new GetModeratorsRequest(broadcasterId: '1234', userIds: ['u1']),
                $this->token,
            ),
        );
    }

    #[Test]
    public function add_channel_moderator_posts_query_without_response(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'moderation/moderators', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => 'user-1',
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->addChannelModerator(
            new AddChannelModeratorRequest(broadcasterId: '1234', userId: 'user-1'),
            $this->token,
        );
    }

    #[Test]
    public function remove_channel_moderator_deletes_query(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'moderation/moderators', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => 'user-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->removeChannelModerator(
            new RemoveChannelModeratorRequest(broadcasterId: '1234', userId: 'user-1'),
            $this->token,
        );
    }

    #[Test]
    public function get_vips_uses_non_base_channels_path(): void
    {
        $expected = new VipsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channels/vips', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getVips(new GetVipsRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function add_channel_vip_posts_to_channels_path(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'channels/vips', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => 'user-1',
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->addChannelVip(
            new AddChannelVipRequest(broadcasterId: '1234', userId: 'user-1'),
            $this->token,
        );
    }

    #[Test]
    public function remove_channel_vip_deletes_from_channels_path(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'channels/vips', $this->token, [
                'broadcaster_id' => '1234',
                'user_id'        => 'user-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->removeChannelVip(
            new RemoveChannelVipRequest(broadcasterId: '1234', userId: 'user-1'),
            $this->token,
        );
    }

    #[Test]
    public function update_shield_mode_status_puts_normalized_body(): void
    {
        $status = new UpdateShieldModeStatus(isActive: true);
        $normalized = ['is_active' => true];
        $expected = new ShieldModeStatusResponse(data: []);

        $this->normalizer->expects($this->once())->method('normalize')->with($status)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'moderation/shield_mode', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ], $normalized)
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateShieldModeStatus(
                new UpdateShieldModeStatusRequest(broadcasterId: '1234', moderatorId: 'mod-1', status: $status),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_moderated_channels_omits_null_after(): void
    {
        $expected = new ModeratedChannelsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'moderation/channels', $this->token, [
                'user_id' => 'user-1',
                'first'   => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getModeratedChannels(
                new GetModeratedChannelsRequest(userId: 'user-1'),
                $this->token,
            ),
        );
    }
}
