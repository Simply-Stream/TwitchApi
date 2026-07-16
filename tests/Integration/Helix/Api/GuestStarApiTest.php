<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
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
use SimplyStream\TwitchApi\Helix\Models\GuestStar\ChannelGuestStarSetting;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\GuestStarInvite;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\GuestStarSession;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\MediaSettings;
use SimplyStream\TwitchApi\Helix\Models\GuestStar\UpdateChannelGuestStarSetting;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsGuestStarApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(GuestStarApi::class)]
final class GuestStarApiTest extends TestCase
{
    use BuildsGuestStarApi;

    /** @return array<string, mixed> */
    private function sessionPayload(): array
    {
        return [
            'id'                => '2KFRQbFtpmfyD3IevNRnCzOzx6h',
            'guests'            => [],
            'slot_id'           => '1',
            'is_live'           => true,
            'user_id'           => '9876',
            'user_display_name' => 'Cool_User',
            'user_login'        => 'cool_user',
            'volume'            => 100,
            'assigned_at'       => '2023-01-02T04:16:53.325Z',
            'audio_settings'    => [
                'is_host_enabled'  => true,
                'is_guest_enabled' => true,
                'is_available'     => true,
            ],
            'video_settings'    => [
                'is_host_enabled'  => true,
                'is_guest_enabled' => true,
                'is_available'     => true,
            ],
        ];
    }

    #[Test]
    public function get_channel_guest_star_settings_denormalizes_the_booleans(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'is_moderator_send_live_enabled'   => true,
                'slot_count'                       => 4,
                'is_browser_source_audio_enabled'  => true,
                'group_layout'                     => 'TILED_LAYOUT',
                'browser_source_token'             => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelGuestStarSettings(
            new GetChannelGuestStarSettingsRequest(broadcasterId: '9321049', moderatorId: '9321049'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=9321049&moderator_id=9321049',
            $http->getLastRequest()->getUri()->getQuery(),
        );

        $this->assertInstanceOf(ChannelGuestStarSettingsResponse::class, $response);
        $settings = $response->data[0];
        $this->assertInstanceOf(ChannelGuestStarSetting::class, $settings);
        $this->assertTrue($settings->isModeratorSendLiveEnabled);
        $this->assertSame(4, $settings->slotCount);
        $this->assertTrue($settings->isBrowserSourceAudioEnabled);
        $this->assertSame('TILED_LAYOUT', $settings->groupLayout);
    }

    #[Test]
    public function update_channel_guest_star_settings_spreads_the_normalized_body_into_the_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->updateChannelGuestStarSettings(
            new UpdateChannelGuestStarSettingsRequest(
                broadcasterId: '9321049',
                settings: new UpdateChannelGuestStarSetting(slotCount: 5, groupLayout: 'TILED_LAYOUT'),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/helix/guest_star/channel_settings', $request->getUri()->getPath());

        // Only the fields that were set may appear; the rest are dropped by SKIP_NULL_VALUES.
        $this->assertSame(
            'broadcaster_id=9321049&slot_count=5&group_layout=TILED_LAYOUT',
            $request->getUri()->getQuery(),
        );
    }

    #[Test]
    public function update_channel_guest_star_settings_keeps_a_false_boolean(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->updateChannelGuestStarSettings(
            new UpdateChannelGuestStarSettingsRequest(
                broadcasterId: '9321049',
                settings: new UpdateChannelGuestStarSetting(isBrowserSourceAudioEnabled: false),
            ),
            new StaticAccessToken(),
        );

        // false is not null — it must survive both SKIP_NULL_VALUES and array_filter.
        $this->assertSame(
            'broadcaster_id=9321049&is_browser_source_audio_enabled=false',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function get_guest_star_session_denormalizes_the_media_settings(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->sessionPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getGuestStarSession(
            new GetGuestStarSessionRequest(broadcasterId: '9321049', moderatorId: '9321049'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(GuestStarSessionResponse::class, $response);
        $session = $response->data[0];
        $this->assertInstanceOf(GuestStarSession::class, $session);
        $this->assertSame('1', $session->slotId);
        $this->assertTrue($session->isLive);
        $this->assertSame(100, $session->volume);
        $this->assertSame([], $session->guests);
        $this->assertInstanceOf(DateTimeInterface::class, $session->assignedAt);

        $this->assertInstanceOf(MediaSettings::class, $session->audioSettings);
        $this->assertTrue($session->audioSettings->isHostEnabled);
        $this->assertTrue($session->audioSettings->isGuestEnabled);
        $this->assertTrue($session->audioSettings->isAvailable);

        $this->assertInstanceOf(MediaSettings::class, $session->videoSettings);
    }

    #[Test]
    public function create_guest_star_session_posts_with_a_query_only(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->sessionPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createGuestStarSession(
            new CreateGuestStarSessionRequest(broadcasterId: '9321049'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('broadcaster_id=9321049', $request->getUri()->getQuery());
        $this->assertSame('[]', (string) $request->getBody());

        $this->assertInstanceOf(GuestStarSessionResponse::class, $response);
    }

    #[Test]
    public function end_guest_star_session_deletes_with_a_response(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->sessionPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->endGuestStarSession(
            new EndGuestStarSessionRequest(broadcasterId: '9321049', sessionId: '2KFRQbFtpmfyD3IevNRnCzOzx6h'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame(
            'broadcaster_id=9321049&session_id=2KFRQbFtpmfyD3IevNRnCzOzx6h',
            $request->getUri()->getQuery(),
        );

        // Unlike most DELETEs, this one returns a body.
        $this->assertInstanceOf(GuestStarSessionResponse::class, $response);
        $this->assertCount(1, $response->data);
    }

    #[Test]
    public function get_guest_star_invites_denormalizes_the_invite_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'            => '9321049',
                'invited_at'         => '2023-01-02T04:16:53.325Z',
                'status'             => 'INVITED',
                'is_video_enabled'   => false,
                'is_audio_enabled'   => true,
                'is_video_available' => true,
                'is_audio_available' => true,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getGuestStarInvites(
            new GetGuestStarInvitesRequest(
                broadcasterId: '9321049',
                moderatorId: '9321049',
                sessionId: '2KFRQbFtpmfyD3IevNRnCzOzx6h',
            ),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(GuestStarInvitesResponse::class, $response);
        $invite = $response->data[0];
        $this->assertInstanceOf(GuestStarInvite::class, $invite);
        $this->assertSame('INVITED', $invite->status);
        $this->assertFalse($invite->isVideoEnabled);
        $this->assertTrue($invite->isAudioEnabled);
        $this->assertInstanceOf(DateTimeInterface::class, $invite->invitedAt);
    }

    #[Test]
    public function send_guest_star_invite_posts_query_only(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->sendGuestStarInvite(
            new SendGuestStarInviteRequest(
                broadcasterId: '9321049',
                moderatorId: '9321049',
                sessionId: 'session-1',
                guestId: '144601104',
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=9321049&moderator_id=9321049&session_id=session-1&guest_id=144601104',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function delete_guest_star_invite_sends_a_delete(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteGuestStarInvite(
            new DeleteGuestStarInviteRequest(
                broadcasterId: '9321049',
                moderatorId: '9321049',
                sessionId: 'session-1',
                guestId: '144601104',
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/helix/guest_star/invites', $request->getUri()->getPath());
    }

    #[Test]
    public function assign_guest_star_slot_posts_query_only(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->assignGuestStarSlot(
            new AssignGuestStarSlotRequest(
                broadcasterId: '9321049',
                moderatorId: '9321049',
                sessionId: 'session-1',
                guestId: '144601104',
                slotId: '1',
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=9321049&moderator_id=9321049&session_id=session-1&guest_id=144601104&slot_id=1',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function update_guest_star_slot_omits_a_null_destination(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->updateGuestStarSlot(
            new UpdateGuestStarSlotRequest(
                broadcasterId: '9321049',
                moderatorId: '9321049',
                sessionId: 'session-1',
                sourceSlotId: '1',
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame(
            'broadcaster_id=9321049&moderator_id=9321049&session_id=session-1&source_slot_id=1',
            $request->getUri()->getQuery(),
        );
    }

    #[Test]
    public function delete_guest_star_slot_sends_should_reinvite_as_a_literal(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteGuestStarSlot(
            new DeleteGuestStarSlotRequest(
                broadcasterId: '9321049',
                moderatorId: '9321049',
                sessionId: 'session-1',
                guestId: '144601104',
                slotId: '1',
                shouldReinviteGuest: false,
            ),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertSame('false', $query['should_reinvite_guest']);
    }

    #[Test]
    public function update_guest_star_slot_settings_omits_null_flags(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->updateGuestStarSlotSettings(
            new UpdateGuestStarSlotSettingsRequest(
                broadcasterId: '9321049',
                moderatorId: '9321049',
                sessionId: 'session-1',
                slotId: '1',
                isAudioEnabled: false,
                volume: 50,
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/guest_star/slot_settings', $request->getUri()->getPath());
        $this->assertSame(
            'broadcaster_id=9321049&moderator_id=9321049&session_id=session-1&slot_id=1&is_audio_enabled=false&volume=50',
            $request->getUri()->getQuery(),
        );
    }
}
