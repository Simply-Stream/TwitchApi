<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\AddBlockedTermRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\AddChannelModeratorRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\AddChannelVipRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\BanUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\CheckAutoModStatusRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\DeleteChatMessagesRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetAutoModSettingsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetBannedUsersRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetBlockedTermsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetModeratedChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetModeratorsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetShieldModeStatusRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\GetVipsRequest;
use SimplyStream\TwitchApi\Helix\Api\Moderation\Request\RemoveBlockedTermRequest;
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
use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModSettings;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BannedUser;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BanUser;
use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ModeratedChannel;
use SimplyStream\TwitchApi\Helix\Models\Moderation\Moderator;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ShieldModeStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateAutoModSettings;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateShieldModeStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UserBan;
use SimplyStream\TwitchApi\Helix\Models\Moderation\Vip;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsModerationApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ModerationApi::class)]
final class ModerationApiTest extends TestCase
{
    use BuildsModerationApi;

    #[Test]
    public function check_automod_status_wraps_the_messages_in_a_data_key(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                ['msg_id' => '123', 'is_permitted' => true],
                ['msg_id' => '393', 'is_permitted' => false],
            ],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->checkAutoModStatus(
            new CheckAutoModStatusRequest(
                broadcasterId: '12345',
                status: new CheckAutoModStatus([
                    new AutoModMessage(msgId: '123', msgText: 'Hello World!'),
                    new AutoModMessage(msgId: '393', msgText: 'Boooooo!'),
                ]),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/moderation/enforcements/status', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=12345', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'data' => [
                ['msg_id' => '123', 'msg_text' => 'Hello World!'],
                ['msg_id' => '393', 'msg_text' => 'Boooooo!'],
            ],
        ], $body);

        $this->assertInstanceOf(AutoModStatusResponse::class, $response);
        $this->assertInstanceOf(AutoModStatus::class, $response->data[0]);
        $this->assertTrue($response->data[0]->isPermitted);
        $this->assertFalse($response->data[1]->isPermitted);
    }

    #[Test]
    public function get_automod_settings_accepts_a_null_overall_level(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'            => '713936733',
                'moderator_id'              => '5678',
                'overall_level'             => null,
                'disability'                => 0,
                'aggression'                => 0,
                'sexuality_sex_or_gender'   => 0,
                'misogyny'                  => 0,
                'bullying'                  => 0,
                'swearing'                  => 0,
                'race_ethnicity_or_religion' => 0,
                'sex_based_terms'           => 0,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getAutoModSettings(
            new GetAutoModSettingsRequest(broadcasterId: '713936733', moderatorId: '5678'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(AutoModSettingsResponse::class, $response);
        $settings = $response->data[0];
        $this->assertInstanceOf(AutoModSettings::class, $settings);

        // Null when individual levels are set instead of an overall level.
        $this->assertNull($settings->overallLevel);
        $this->assertSame(0, $settings->disability);
    }

    #[Test]
    public function update_automod_settings_puts_the_normalized_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'            => '713936733',
                'moderator_id'              => '5678',
                'overall_level'             => 3,
                'disability'                => 3,
                'aggression'                => 3,
                'sexuality_sex_or_gender'   => 3,
                'misogyny'                  => 3,
                'bullying'                  => 3,
                'swearing'                  => 3,
                'race_ethnicity_or_religion' => 3,
                'sex_based_terms'           => 3,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateAutoModSettings(
            new UpdateAutoModSettingsRequest(
                broadcasterId: '713936733',
                moderatorId: '5678',
                settings: new UpdateAutoModSettings(
                    aggression: 0,
                    bullying: 0,
                    disability: 0,
                    misogyny: 0,
                    raceEthnicityOrReligion: 0,
                    sexBasedTerms: 0,
                    sexualitySexOrGender: 0,
                    swearing: 0,
                    overallLevel: 3,
                ),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame(
            'broadcaster_id=713936733&moderator_id=5678',
            $request->getUri()->getQuery(),
        );

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(3, $body['overall_level']);
        $this->assertSame(0, $body['race_ethnicity_or_religion']);
        $this->assertSame(0, $body['sexuality_sex_or_gender']);
    }

    #[Test]
    public function get_banned_users_accepts_an_empty_expires_at(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'         => '423374343',
                'user_login'      => 'glowillig',
                'user_name'       => 'glowillig',
                'expires_at'      => '2022-03-15T02:00:28Z',
                'created_at'      => '2022-03-15T01:30:28Z',
                'reason'          => 'Does not like pineapple on pizza.',
                'moderator_id'    => '141981764',
                'moderator_login' => 'twitchdev',
                'moderator_name'  => 'TwitchDev',
            ], [
                'user_id'         => '424596340',
                'user_login'      => 'quotrok',
                'user_name'       => 'quotrok',
                'expires_at'      => null,
                'created_at'      => '2022-08-07T02:07:55Z',
                'reason'          => 'Permanent ban',
                'moderator_id'    => '141981764',
                'moderator_login' => 'twitchdev',
                'moderator_name'  => 'TwitchDev',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getBannedUsers(
            new GetBannedUsersRequest(broadcasterId: '198704263'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(BannedUsersResponse::class, $response);

        $timeout = $response->data[0];
        $this->assertInstanceOf(BannedUser::class, $timeout);
        $this->assertInstanceOf(DateTimeInterface::class, $timeout->expiresAt);

        // A permanent ban has no expiry.
        $this->assertNull($response->data[1]->expiresAt);
        $this->assertSame('Permanent ban', $response->data[1]->reason);
    }

    #[Test]
    public function ban_user_posts_the_normalized_ban(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id' => '1234',
                'moderator_id'   => '5678',
                'user_id'        => '9876',
                'created_at'     => '2021-09-28T18:22:31Z',
                'end_time'       => null,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->banUser(
            new BanUserRequest(
                broadcasterId: '1234',
                moderatorId: '5678',
                ban: new BanUser(userId: '9876', reason: 'no reason'),
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);

        // A permanent ban omits the duration entirely.
        $this->assertSame(['user_id' => '9876', 'reason' => 'no reason'], $body);

        $this->assertInstanceOf(BanUserResponse::class, $response);
        $ban = $response->data[0];
        $this->assertInstanceOf(UserBan::class, $ban);
        $this->assertNull($ban->endTime);
        $this->assertInstanceOf(DateTimeInterface::class, $ban->createdAt);
    }

    #[Test]
    public function ban_user_sends_a_duration_for_timeouts(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id' => '1234',
                'moderator_id'   => '5678',
                'user_id'        => '9876',
                'created_at'     => '2021-09-28T19:27:31Z',
                'end_time'       => '2021-09-28T19:22:31Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->banUser(
            new BanUserRequest(
                broadcasterId: '1234',
                moderatorId: '5678',
                ban: new BanUser(userId: '9876', duration: 300, reason: 'no reason'),
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(300, $body['duration']);

        $this->assertInstanceOf(DateTimeInterface::class, $response->data[0]->endTime);
    }

    #[Test]
    public function unban_user_sends_a_delete(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->unbanUser(
            new UnbanUserRequest(broadcasterId: '1234', moderatorId: '5678', userId: '9876'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/helix/moderation/bans', $request->getUri()->getPath());
        $this->assertSame(
            'broadcaster_id=1234&moderator_id=5678&user_id=9876',
            $request->getUri()->getQuery(),
        );
    }

    #[Test]
    public function get_blocked_terms_denormalizes_the_timestamps(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id' => '713936733',
                'moderator_id'   => '5678',
                'id'             => '520e4d4e-0cda-49c7-821e-e5ef4f88c2f2',
                'text'           => 'A phrase I’m not fond of',
                'created_at'     => '2021-09-29T19:45:37Z',
                'updated_at'     => '2021-09-29T19:45:37Z',
                'expires_at'     => null,
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getBlockedTerms(
            new GetBlockedTermsRequest(broadcasterId: '713936733', moderatorId: '5678'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(BlockedTermsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);
        $this->assertCount(1, $response->data);
    }

    #[Test]
    public function add_blocked_term_posts_the_normalized_term(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id' => '713936733',
                'moderator_id'   => '5678',
                'id'             => '520e4d4e-0cda-49c7-821e-e5ef4f88c2f2',
                'text'           => 'crac*',
                'created_at'     => '2021-09-29T19:45:37Z',
                'updated_at'     => '2021-09-29T19:45:37Z',
                'expires_at'     => null,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->addBlockedTerm(
            new AddBlockedTermRequest(
                broadcasterId: '713936733',
                moderatorId: '5678',
                term: new AddBlockedTerm(text: 'crac*'),
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['text' => 'crac*'], $body);
    }

    #[Test]
    public function remove_blocked_term_sends_a_delete(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->removeBlockedTerm(
            new RemoveBlockedTermRequest(
                broadcasterId: '713936733',
                moderatorId: '5678',
                id: 'c9fc79b8-0f63-4ef7-9d38-efd811e74ac2',
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=713936733&moderator_id=5678&id=c9fc79b8-0f63-4ef7-9d38-efd811e74ac2',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function delete_chat_messages_omits_a_null_message_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteChatMessages(
            new DeleteChatMessagesRequest(broadcasterId: '11111', moderatorId: '44444'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=11111&moderator_id=44444',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function get_moderators_repeats_the_user_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'    => '424596340',
                'user_login' => 'quotrok',
                'user_name'  => 'quotrok',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getModerators(
            new GetModeratorsRequest(broadcasterId: '198704263', userIds: ['424596340', '424596341']),
            new StaticAccessToken(),
        );

        $this->assertStringContainsString(
            'user_id=424596340&user_id=424596341',
            $http->getLastRequest()->getUri()->getQuery(),
        );

        $this->assertInstanceOf(ModeratorsResponse::class, $response);
        $this->assertInstanceOf(Moderator::class, $response->data[0]);
    }

    #[Test]
    public function add_channel_moderator_posts_query_only(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->addChannelModerator(
            new AddChannelModeratorRequest(broadcasterId: '11111', userId: '44444'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/moderation/moderators', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=11111&user_id=44444', $request->getUri()->getQuery());
    }

    #[Test]
    public function get_vips_uses_the_channels_path(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'    => '11111',
                'user_name'  => 'UserDisplayName',
                'user_login' => 'userloginname',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getVips(
            new GetVipsRequest(broadcasterId: '123456'),
            new StaticAccessToken(),
        );

        // Not under /moderation despite living on ModerationApi.
        $this->assertSame('/helix/channels/vips', $http->getLastRequest()->getUri()->getPath());

        $this->assertInstanceOf(VipsResponse::class, $response);
        $this->assertInstanceOf(Vip::class, $response->data[0]);
        $this->assertSame('UserDisplayName', $response->data[0]->userName);
    }

    #[Test]
    public function add_channel_vip_posts_to_the_channels_path(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->addChannelVip(
            new AddChannelVipRequest(broadcasterId: '123456', userId: '98765'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/channels/vips', $request->getUri()->getPath());
    }

    #[Test]
    public function remove_channel_vip_deletes_from_the_channels_path(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->removeChannelVip(
            new RemoveChannelVipRequest(broadcasterId: '123456', userId: '98765'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/helix/channels/vips', $request->getUri()->getPath());
    }

    #[Test]
    public function update_shield_mode_status_puts_the_normalized_status(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'is_active'         => true,
                'moderator_id'      => '98765',
                'moderator_login'   => 'simplysimple',
                'moderator_name'    => 'SimplySimple',
                'last_activated_at' => '2022-07-26T17:16:03.123Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->updateShieldModeStatus(
            new UpdateShieldModeStatusRequest(
                broadcasterId: '12345',
                moderatorId: '98765',
                status: new UpdateShieldModeStatus(isActive: true),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['is_active' => true], $body);

        $this->assertInstanceOf(ShieldModeStatusResponse::class, $response);
        $status = $response->data[0];
        $this->assertInstanceOf(ShieldModeStatus::class, $status);
        $this->assertTrue($status->isActive);
        $this->assertInstanceOf(DateTimeInterface::class, $status->lastActivatedAt);
    }

    #[Test]
    public function get_shield_mode_status_denormalizes_an_inactive_status(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'is_active'         => false,
                'moderator_id'      => '98765',
                'moderator_login'   => 'simplysimple',
                'moderator_name'    => 'SimplySimple',
                'last_activated_at' => '2022-07-26T17:16:03.123Z',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getShieldModeStatus(
            new GetShieldModeStatusRequest(broadcasterId: '12345', moderatorId: '98765'),
            new StaticAccessToken(),
        );

        $this->assertFalse($response->data[0]->isActive);
    }

    #[Test]
    public function get_moderated_channels_denormalizes_the_channel_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'    => '12345',
                'broadcaster_login' => 'grateful_broadcaster',
                'broadcaster_name'  => 'Grateful_Broadcaster',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getModeratedChannels(
            new GetModeratedChannelsRequest(userId: '154315414'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/moderation/channels', $request->getUri()->getPath());
        $this->assertSame('user_id=154315414&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(ModeratedChannelsResponse::class, $response);
        $this->assertInstanceOf(ModeratedChannel::class, $response->data[0]);
        $this->assertSame('Grateful_Broadcaster', $response->data[0]->broadcasterName);
    }
}
