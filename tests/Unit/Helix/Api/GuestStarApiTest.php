<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
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
use SimplyStream\TwitchApi\Helix\Api\GuestStarApi;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\UpdateChannelGuestStarSetting;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(GuestStarApi::class)]
final class GuestStarApiTest extends TestCase
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

    private function api(): GuestStarApi
    {
        return new GuestStarApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_channel_guest_star_settings_forwards_ids(): void
    {
        $expected = new ChannelGuestStarSettingsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'guest_star/channel_settings', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelGuestStarSettings(
                new GetChannelGuestStarSettingsRequest(broadcasterId: '1234', moderatorId: 'mod-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function update_channel_guest_star_settings_spreads_normalized_settings_into_query(): void
    {
        $settings = new UpdateChannelGuestStarSetting();
        $normalized = ['is_moderator_send_live_enabled' => true, 'slot_count' => 5];

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($settings)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'guest_star/channel_settings', $this->token, [
                'broadcaster_id'                 => '1234',
                'is_moderator_send_live_enabled' => true,
                'slot_count'                     => 5,
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->updateChannelGuestStarSettings(
            new UpdateChannelGuestStarSettingsRequest(broadcasterId: '1234', settings: $settings),
            $this->token,
        );
    }

    #[Test]
    public function get_guest_star_session_forwards_ids(): void
    {
        $expected = new GuestStarSessionResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'guest_star/session', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getGuestStarSession(
                new GetGuestStarSessionRequest(broadcasterId: '1234', moderatorId: 'mod-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function create_guest_star_session_posts_query_only(): void
    {
        $expected = new GuestStarSessionResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'guest_star/session', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createGuestStarSession(
                new CreateGuestStarSessionRequest(broadcasterId: '1234'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function end_guest_star_session_deletes_with_response(): void
    {
        $raw = ['data' => []];
        $expected = new GuestStarSessionResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'guest_star/session', $this->token, [
                'broadcaster_id' => '1234',
                'session_id'     => 'session-1',
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, GuestStarSessionResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->endGuestStarSession(
                new EndGuestStarSessionRequest(broadcasterId: '1234', sessionId: 'session-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_guest_star_invites_forwards_ids(): void
    {
        $expected = new GuestStarInvitesResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'guest_star/invites', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'session_id'     => 'session-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getGuestStarInvites(
                new GetGuestStarInvitesRequest(broadcasterId: '1234', moderatorId: 'mod-1', sessionId: 'session-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function send_guest_star_invite_posts_query_without_response(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'guest_star/invites', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'session_id'     => 'session-1',
                'guest_id'       => 'guest-1',
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->sendGuestStarInvite(
            new SendGuestStarInviteRequest(
                broadcasterId: '1234',
                moderatorId: 'mod-1',
                sessionId: 'session-1',
                guestId: 'guest-1',
            ),
            $this->token,
        );
    }

    #[Test]
    public function delete_guest_star_invite_deletes_query(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'guest_star/invites', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'session_id'     => 'session-1',
                'guest_id'       => 'guest-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->deleteGuestStarInvite(
            new DeleteGuestStarInviteRequest(
                broadcasterId: '1234',
                moderatorId: 'mod-1',
                sessionId: 'session-1',
                guestId: 'guest-1',
            ),
            $this->token,
        );
    }

    #[Test]
    public function assign_guest_star_slot_posts_query_without_response(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'guest_star/slot', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'session_id'     => 'session-1',
                'guest_id'       => 'guest-1',
                'slot_id'        => '2',
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->assignGuestStarSlot(
            new AssignGuestStarSlotRequest(
                broadcasterId: '1234',
                moderatorId: 'mod-1',
                sessionId: 'session-1',
                guestId: 'guest-1',
                slotId: '2',
            ),
            $this->token,
        );
    }

    #[Test]
    public function update_guest_star_slot_patches_query_and_omits_null_destination(): void
    {
        // destinationSlotId defaults to null -> filtered out.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'guest_star/slot', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'session_id'     => 'session-1',
                'source_slot_id' => '2',
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->updateGuestStarSlot(
            new UpdateGuestStarSlotRequest(
                broadcasterId: '1234',
                moderatorId: 'mod-1',
                sessionId: 'session-1',
                sourceSlotId: '2',
            ),
            $this->token,
        );
    }

    #[Test]
    public function delete_guest_star_slot_deletes_query_and_omits_null_reinvite(): void
    {
        // shouldReinviteGuest defaults to null -> filtered out.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'guest_star/slot', $this->token, [
                'broadcaster_id' => '1234',
                'moderator_id'   => 'mod-1',
                'session_id'     => 'session-1',
                'guest_id'       => 'guest-1',
                'slot_id'        => '2',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->deleteGuestStarSlot(
            new DeleteGuestStarSlotRequest(
                broadcasterId: '1234',
                moderatorId: 'mod-1',
                sessionId: 'session-1',
                guestId: 'guest-1',
                slotId: '2',
            ),
            $this->token,
        );
    }

    #[Test]
    public function update_guest_star_slot_settings_patches_query_and_omits_null_flags(): void
    {
        // Only isAudioEnabled is set; the other flags and volume stay null and are filtered.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'guest_star/slot_settings', $this->token, [
                'broadcaster_id'   => '1234',
                'moderator_id'     => 'mod-1',
                'session_id'       => 'session-1',
                'slot_id'          => '2',
                'is_audio_enabled' => true,
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->updateGuestStarSlotSettings(
            new UpdateGuestStarSlotSettingsRequest(
                broadcasterId: '1234',
                moderatorId: 'mod-1',
                sessionId: 'session-1',
                slotId: '2',
                isAudioEnabled: true,
            ),
            $this->token,
        );
    }
}
