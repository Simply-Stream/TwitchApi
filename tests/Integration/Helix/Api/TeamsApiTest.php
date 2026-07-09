<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Teams\Request\GetChannelTeamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Teams\Request\GetTeamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Teams\Response\ChannelTeamsResponse;
use SimplyStream\TwitchApi\Helix\Api\Teams\Response\TeamsResponse;
use SimplyStream\TwitchApi\Helix\Api\TeamsApi;
use SimplyStream\TwitchApi\Helix\Models\Teams\ChannelTeam;
use SimplyStream\TwitchApi\Helix\Models\Teams\Member;
use SimplyStream\TwitchApi\Helix\Models\Teams\Team;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsTeamsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(TeamsApi::class)]
final class TeamsApiTest extends TestCase
{
    use BuildsTeamsApi;

    #[Test]
    public function get_channel_teams_denormalizes_the_team_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'       => '96909659',
                'broadcaster_name'     => 'CSharpFritz',
                'broadcaster_login'    => 'csharpfritz',
                'background_image_url' => null,
                'banner'               => null,
                'created_at'           => '2019-02-11T12:09:22Z',
                'updated_at'           => '2020-11-18T15:56:41Z',
                'info'                 => '<p>An outgoing and enthusiastic group of friendly channels</p>',
                'thumbnail_url'        => 'https://static-cdn.jtvnw.net/jtv_user_pictures/team-livecoders-team_logo_image-bf1d9a87ca81432687de60e24ad9593d-600x600.png',
                'team_name'            => 'livecoders',
                'team_display_name'    => 'Live Coders',
                'id'                   => '6358',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelTeams(
            new GetChannelTeamsRequest(broadcasterId: '96909659'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/teams/channel', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=96909659', $request->getUri()->getQuery());

        $this->assertInstanceOf(ChannelTeamsResponse::class, $response);
        $this->assertCount(1, $response->data);

        $team = $response->data[0];
        $this->assertInstanceOf(ChannelTeam::class, $team);
        $this->assertSame('6358', $team->id);
        $this->assertSame('livecoders', $team->teamName);
        $this->assertSame('Live Coders', $team->teamDisplayName);
        $this->assertSame('CSharpFritz', $team->broadcasterName);
        $this->assertInstanceOf(DateTimeInterface::class, $team->createdAt);
        $this->assertInstanceOf(DateTimeInterface::class, $team->updatedAt);

        // Optional images stay null.
        $this->assertNull($team->backgroundImageUrl);
        $this->assertNull($team->banner);
    }

    #[Test]
    public function get_teams_denormalizes_the_member_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'users' => [
                    [
                        'user_id'    => '278217731',
                        'user_name'  => 'mastermndio',
                        'user_login' => 'mastermndio',
                    ],
                    [
                        'user_id'    => '41284990',
                        'user_name'  => 'jenninexus',
                        'user_login' => 'jenninexus',
                    ],
                ],
                'background_image_url' => null,
                'banner'               => null,
                'created_at'           => '2019-02-11T12:09:22Z',
                'updated_at'           => '2020-11-18T15:56:41Z',
                'info'                 => '<p>An outgoing and enthusiastic group of friendly channels</p>',
                'thumbnail_url'        => 'https://static-cdn.jtvnw.net/jtv_user_pictures/team-livecoders-team_logo_image.png',
                'team_name'            => 'livecoders',
                'team_display_name'    => 'Live Coders',
                'id'                   => '6358',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getTeams(
            new GetTeamsRequest(id: '6358'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/teams', $request->getUri()->getPath());
        $this->assertSame('id=6358', $request->getUri()->getQuery());

        $this->assertInstanceOf(TeamsResponse::class, $response);

        $team = $response->data[0];
        $this->assertInstanceOf(Team::class, $team);
        $this->assertSame('Live Coders', $team->teamDisplayName);

        $this->assertCount(2, $team->users);
        $member = $team->users[0];
        $this->assertInstanceOf(Member::class, $member);
        $this->assertSame('278217731', $member->userId);
        $this->assertSame('mastermndio', $member->userName);
        $this->assertSame('mastermndio', $member->userLogin);
    }

    #[Test]
    public function get_teams_forwards_the_name_filter(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getTeams(
            new GetTeamsRequest(name: 'livecoders'),
            new StaticAccessToken(),
        );

        $this->assertSame('name=livecoders', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function get_teams_accepts_a_populated_banner(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'users'                => [],
                'background_image_url' => 'https://static-cdn.jtvnw.net/team-background.png',
                'banner'               => 'https://static-cdn.jtvnw.net/team-banner.png',
                'created_at'           => '2019-02-11T12:09:22Z',
                'updated_at'           => '2020-11-18T15:56:41Z',
                'info'                 => '',
                'thumbnail_url'        => 'https://static-cdn.jtvnw.net/team-logo.png',
                'team_name'            => 'livecoders',
                'team_display_name'    => 'Live Coders',
                'id'                   => '6358',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getTeams(
            new GetTeamsRequest(id: '6358'),
            new StaticAccessToken(),
        );

        $team = $response->data[0];
        $this->assertSame('https://static-cdn.jtvnw.net/team-background.png', $team->backgroundImageUrl);
        $this->assertSame('https://static-cdn.jtvnw.net/team-banner.png', $team->banner);
        $this->assertSame([], $team->users);
    }
}
