<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelEditorsRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelFollowersRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetFollowedChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\ModifyChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelEditorsResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelFollowersResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelInformationResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\FollowedChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\ChannelsApi;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelEditor;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelFollow;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelInformation;
use SimplyStream\TwitchApi\Helix\Models\Channels\FollowedChannel;
use SimplyStream\TwitchApi\Helix\Models\Channels\Label;
use SimplyStream\TwitchApi\Helix\Models\Channels\ModifyChannelInformation;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsChannelsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ChannelsApi::class)]
final class ChannelsApiTest extends TestCase
{
    use BuildsChannelsApi;

    #[Test]
    public function get_channel_information_repeats_broadcaster_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'       => '141981764',
                'broadcaster_login'    => 'twitchdev',
                'broadcaster_name'     => 'TwitchDev',
                'broadcaster_language' => 'en',
                'game_name'            => 'Science & Technology',
                'game_id'              => '509670',
                'title'                => 'TwitchDev Monthly Update',
                'delay'                => 0,
                'tags'                 => ['DevsInTheKnow'],
                'content_classification_labels' => ['Gambling', 'DrugsIntoxication'],
                'is_branded_content'   => false,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelInformation(
            new GetChannelInformationRequest(broadcasterIds: ['141981764', '141981765']),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=141981764&broadcaster_id=141981765',
            $http->getLastRequest()->getUri()->getQuery(),
        );

        $this->assertInstanceOf(ChannelInformationResponse::class, $response);
        $channel = $response->data[0];
        $this->assertInstanceOf(ChannelInformation::class, $channel);
        $this->assertSame('TwitchDev', $channel->broadcasterName);
        $this->assertSame(0, $channel->delay);
        $this->assertSame(['DevsInTheKnow'], $channel->tags);
        $this->assertSame(['Gambling', 'DrugsIntoxication'], $channel->contentClassificationLabels);
        $this->assertFalse($channel->isBrandedContent);
    }

    #[Test]
    public function modify_channel_information_omits_null_fields_from_the_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->modifyChannelInformation(
            new ModifyChannelInformationRequest(
                broadcasterId: '141981764',
                information: new ModifyChannelInformation(title: 'New title'),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame('broadcaster_id=141981764', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        // Twitch treats absent fields as "leave unchanged" — a sparse update must stay sparse.
        $this->assertSame(['title' => 'New title'], $body);
    }

    #[Test]
    public function modify_channel_information_normalizes_the_label_objects(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->modifyChannelInformation(
            new ModifyChannelInformationRequest(
                broadcasterId: '141981764',
                information: new ModifyChannelInformation(
                    gameId: '509670',
                    tags: ['DevsInTheKnow'],
                    contentClassificationLabels: [new Label(id: 'Gambling', isEnabled: true)],
                    isBrandedContent: true,
                ),
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('509670', $body['game_id']);
        $this->assertSame(['DevsInTheKnow'], $body['tags']);
        $this->assertTrue($body['is_branded_content']);
        $this->assertSame(
            [['id' => 'Gambling', 'is_enabled' => true]],
            $body['content_classification_labels'],
        );
    }

    #[Test]
    public function get_channel_editors_denormalizes_the_created_at_timestamp(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'    => '182891647',
                'user_name'  => 'mauerbac',
                'created_at' => '2019-02-15T21:19:50.380833Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelEditors(
            new GetChannelEditorsRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(ChannelEditorsResponse::class, $response);
        $editor = $response->data[0];
        $this->assertInstanceOf(ChannelEditor::class, $editor);
        $this->assertSame('mauerbac', $editor->userName);
        $this->assertInstanceOf(DateTimeInterface::class, $editor->createdAt);
        $this->assertSame('2019-02-15', $editor->createdAt->format('Y-m-d'));
    }

    #[Test]
    public function get_followed_channels_denormalizes_the_total(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'    => '11111',
                'broadcaster_login' => 'userloginname',
                'broadcaster_name'  => 'UserDisplayName',
                'followed_at'       => '2022-05-24T22:22:08Z',
            ]],
            'total'      => 8,
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getFollowedChannels(
            new GetFollowedChannelsRequest(userId: '123456'),
            new StaticAccessToken(),
        );

        $this->assertSame('user_id=123456&first=20', $http->getLastRequest()->getUri()->getQuery());

        $this->assertInstanceOf(FollowedChannelsResponse::class, $response);
        $this->assertSame(8, $response->total);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $followed = $response->data[0];
        $this->assertInstanceOf(FollowedChannel::class, $followed);
        $this->assertSame('UserDisplayName', $followed->broadcasterName);
        $this->assertInstanceOf(DateTimeInterface::class, $followed->followedAt);
    }

    #[Test]
    public function get_channel_followers_forwards_an_optional_user_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'     => '11111',
                'user_login'  => 'userloginname',
                'user_name'   => 'UserDisplayName',
                'followed_at' => '2022-05-24T22:22:08Z',
            ]],
            'total' => 8,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelFollowers(
            new GetChannelFollowersRequest(broadcasterId: '141981764', userId: '11111'),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertSame('11111', $query['user_id']);
        $this->assertSame('141981764', $query['broadcaster_id']);

        $this->assertInstanceOf(ChannelFollowersResponse::class, $response);
        $this->assertSame(8, $response->total);

        $follower = $response->data[0];
        $this->assertInstanceOf(ChannelFollow::class, $follower);
        $this->assertSame('UserDisplayName', $follower->userName);
        $this->assertNull($response->pagination);
    }
}
