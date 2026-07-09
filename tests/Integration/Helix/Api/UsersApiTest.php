<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Users\BlockReason;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\BlockUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\GetUserActiveExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\GetUserBlockListRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\GetUsersRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\UnblockUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\UpdateUserExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\UpdateUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UserActiveExtensionsResponse;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UserBlockListResponse;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UserExtensionsResponse;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UsersResponse;
use SimplyStream\TwitchApi\Helix\Api\Users\SourceContext;
use SimplyStream\TwitchApi\Helix\Api\UsersApi;
use SimplyStream\TwitchApi\Helix\Models\Users\Component;
use SimplyStream\TwitchApi\Helix\Models\Users\Overlay;
use SimplyStream\TwitchApi\Helix\Models\Users\Panel;
use SimplyStream\TwitchApi\Helix\Models\Users\UpdateUserExtension;
use SimplyStream\TwitchApi\Helix\Models\Users\User;
use SimplyStream\TwitchApi\Helix\Models\Users\UserActiveExtension;
use SimplyStream\TwitchApi\Helix\Models\Users\UserBlock;
use SimplyStream\TwitchApi\Helix\Models\Users\UserExtension;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsUsersApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(UsersApi::class)]
final class UsersApiTest extends TestCase
{
    use BuildsUsersApi;

    /** @return array<string, mixed> */
    private function userPayload(?string $email = null): array
    {
        $payload = [
            'id'                => '141981764',
            'login'             => 'twitchdev',
            'display_name'      => 'TwitchDev',
            'type'              => '',
            'broadcaster_type'  => 'partner',
            'description'       => 'Supporting third-party developers building Twitch integrations',
            'profile_image_url' => 'https://static-cdn.jtvnw.net/jtv_user_pictures/8a6381c7-profile_image-300x300.png',
            'offline_image_url' => 'https://static-cdn.jtvnw.net/jtv_user_pictures/3f13ab61-channel_offline_image.png',
            'view_count'        => 5980557,
            'created_at'        => '2016-12-14T20:32:28Z',
        ];

        if (null !== $email) {
            $payload['email'] = $email;
        }

        return $payload;
    }

    #[Test]
    public function get_users_denormalizes_a_user_without_an_email(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->userPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getUsers(
            new GetUsersRequest(logins: ['twitchdev']),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/users', $request->getUri()->getPath());
        $this->assertSame('login=twitchdev', $request->getUri()->getQuery());

        $this->assertInstanceOf(UsersResponse::class, $response);

        $user = $response->data[0];
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('TwitchDev', $user->displayName);
        $this->assertSame('partner', $user->broadcasterType);
        $this->assertSame('', $user->type);
        $this->assertSame(5980557, $user->viewCount);
        $this->assertInstanceOf(DateTimeInterface::class, $user->createdAt);

        // No user:read:email scope means no email field at all.
        $this->assertNull($user->email);
    }

    #[Test]
    public function get_users_denormalizes_an_email_when_the_scope_allows_it(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->userPayload('not-real@email.com')],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getUsers(
            new GetUsersRequest(ids: ['141981764']),
            new StaticAccessToken(),
        );

        $this->assertSame('not-real@email.com', $response->data[0]->email);
    }

    #[Test]
    public function get_users_repeats_ids_and_logins(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getUsers(
            new GetUsersRequest(ids: ['1', '2'], logins: ['foo', 'bar']),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'id=1&id=2&login=foo&login=bar',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function update_user_sends_an_empty_description_to_clear_it(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->userPayload()],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateUser(
            new UpdateUserRequest(description: ''),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());

        // "?description=" clears the description; omitting it would leave it unchanged.
        $this->assertSame('description=', $request->getUri()->getQuery());
    }

    #[Test]
    public function update_user_omits_the_description_when_null(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->userPayload()],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateUser(
            new UpdateUserRequest(),
            new StaticAccessToken(),
        );

        $this->assertSame('', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function update_user_forwards_a_real_description(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->userPayload()],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateUser(
            new UpdateUserRequest(description: 'BaldAngel'),
            new StaticAccessToken(),
        );

        $this->assertSame('description=BaldAngel', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function get_user_block_list_denormalizes_the_block_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'      => '135093069',
                'user_login'   => 'bluelava',
                'display_name' => 'BlueLava',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getUserBlockList(
            new GetUserBlockListRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/users/blocks', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(UserBlockListResponse::class, $response);
        $this->assertInstanceOf(UserBlock::class, $response->data[0]);
        $this->assertSame('BlueLava', $response->data[0]->displayName);
    }

    #[Test]
    public function block_user_unwraps_both_enums(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->blockUser(
            new BlockUserRequest(
                targetUserId: '198704263',
                sourceContext: SourceContext::Chat,
                reason: BlockReason::Harassment,
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame(
            'target_user_id=198704263&source_context=chat&reason=harassment',
            $request->getUri()->getQuery(),
        );
    }

    #[Test]
    public function block_user_omits_the_optional_enums(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->blockUser(
            new BlockUserRequest(targetUserId: '198704263'),
            new StaticAccessToken(),
        );

        $this->assertSame('target_user_id=198704263', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function unblock_user_sends_a_delete(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->unblockUser(
            new UnblockUserRequest(targetUserId: '198704263'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/helix/users/blocks', $request->getUri()->getPath());
        $this->assertSame('target_user_id=198704263', $request->getUri()->getQuery());
    }

    #[Test]
    public function get_user_extensions_sends_no_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'           => 'wi08ebtatdc7oj83wtl9uxwz807l8b',
                'version'      => '1.1.8',
                'name'         => 'Streamlabs Leaderboard',
                'can_activate' => true,
                'type'         => ['panel'],
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getUserExtensions(new StaticAccessToken());

        $request = $http->getLastRequest();
        $this->assertSame('/helix/users/extensions/list', $request->getUri()->getPath());
        $this->assertSame('', $request->getUri()->getQuery());

        $this->assertInstanceOf(UserExtensionsResponse::class, $response);

        $extension = $response->data[0];
        $this->assertInstanceOf(UserExtension::class, $extension);
        $this->assertSame('Streamlabs Leaderboard', $extension->name);
        $this->assertSame(['panel'], $extension->type);

        // The can_* getter trap: can_activate must land in $canActivate.
        $this->assertTrue($extension->canActivate);
    }

    #[Test]
    public function get_user_active_extensions_denormalizes_the_dictionaries(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                'panel' => [
                    '1' => [
                        'active'  => true,
                        'id'      => 'rh6jq1q334hqc2rr1qlzqbvwlfl3x0',
                        'version' => '1.1.0',
                        'name'    => 'TopClips',
                    ],
                    '2' => [
                        'active' => false,
                    ],
                ],
                'overlay' => [
                    '1' => [
                        'active'  => true,
                        'id'      => 'zfh2irvx2jb4s60f02jq0ajm8vwgka',
                        'version' => '1.0.19',
                        'name'    => 'Streamlabs',
                    ],
                ],
                'component' => [
                    '1' => [
                        'active'  => true,
                        'id'      => 'lqnf3zxk0rv0g7gq92mtmnirjz2cjj',
                        'version' => '0.0.1',
                        'name'    => 'Dev Experience Test',
                        'x'       => 0,
                        'y'       => 0,
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getUserActiveExtensions(
            new GetUserActiveExtensionsRequest(userId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/users/extensions', $request->getUri()->getPath());
        $this->assertSame('user_id=141981764', $request->getUri()->getQuery());

        $this->assertInstanceOf(UserActiveExtensionsResponse::class, $response);

        // data is a single object here, not a list.
        $extensions = $response->data;
        $this->assertInstanceOf(UserActiveExtension::class, $extensions);

        // Keyed by a sequential string, not a list.
        $this->assertArrayHasKey('1', $extensions->panel);
        $this->assertInstanceOf(Panel::class, $extensions->panel['1']);
        $this->assertTrue($extensions->panel['1']->active);
        $this->assertSame('TopClips', $extensions->panel['1']->name);

        // An inactive slot carries no id, version or name.
        $this->assertFalse($extensions->panel['2']->active);
        $this->assertNull($extensions->panel['2']->id);

        $this->assertInstanceOf(Overlay::class, $extensions->overlay['1']);
        $this->assertSame('Streamlabs', $extensions->overlay['1']->name);

        $component = $extensions->component['1'];
        $this->assertInstanceOf(Component::class, $component);
        $this->assertSame(0, $component->x);
        $this->assertSame(0, $component->y);
    }

    #[Test]
    public function get_user_active_extensions_omits_a_null_user_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => ['panel' => [], 'overlay' => [], 'component' => []],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getUserActiveExtensions(
            new GetUserActiveExtensionsRequest(),
            new StaticAccessToken(),
        );

        $this->assertSame('', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function update_user_extensions_sends_the_raw_data_structure(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => ['panel' => [], 'overlay' => [], 'component' => []],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateUserExtensions(
            new UpdateUserExtensionsRequest(
                extensions: new UpdateUserExtension(data: [
                    'panel' => [
                        '1' => ['active' => true, 'id' => 'ext-1', 'version' => '1.0.0'],
                    ],
                ]),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/helix/users/extensions', $request->getUri()->getPath());
        $this->assertSame('', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'data' => [
                'panel' => [
                    '1' => ['active' => true, 'id' => 'ext-1', 'version' => '1.0.0'],
                ],
            ],
        ], $body);
    }
}
