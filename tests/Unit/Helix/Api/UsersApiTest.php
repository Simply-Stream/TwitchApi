<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
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
use SimplyStream\TwitchApi\Helix\Models\Users\UpdateUserExtension;
use SimplyStream\TwitchApi\Helix\Models\Users\UserActiveExtension;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(UsersApi::class)]
final class UsersApiTest extends TestCase
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

    private function api(): UsersApi
    {
        return new UsersApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    private function activeExtensions(): UserActiveExtension
    {
        return new UserActiveExtension(panel: [], overlay: [], component: []);
    }

    #[Test]
    public function get_users_repeats_ids_and_logins(): void
    {
        $raw = ['data' => []];
        $expected = new UsersResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users', $this->token, [
                'id'    => ['1', '2'],
                'login' => ['foo'],
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, UsersResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getUsers(new GetUsersRequest(ids: ['1', '2'], logins: ['foo']), $this->token),
        );
    }

    #[Test]
    public function get_users_omits_empty_lists_when_neither_given(): void
    {
        // Both default to [] normally, but the Request constructor requires at least one -> use only ids here.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users', $this->token, ['id' => ['1']])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new UsersResponse(data: []));

        $this->api()->getUsers(new GetUsersRequest(ids: ['1']), $this->token);
    }

    #[Test]
    public function update_user_keeps_empty_string_description_to_clear_it(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'users', $this->token, ['description' => ''])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new UsersResponse(data: []));

        $this->api()->updateUser(new UpdateUserRequest(description: ''), $this->token);
    }

    #[Test]
    public function update_user_omits_description_when_null(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'users', $this->token, [])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new UsersResponse(data: []));

        $this->api()->updateUser(new UpdateUserRequest(), $this->token);
    }

    #[Test]
    public function update_user_forwards_a_real_description(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'users', $this->token, ['description' => 'New description'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new UsersResponse(data: []));

        $this->api()->updateUser(new UpdateUserRequest(description: 'New description'), $this->token);
    }

    #[Test]
    public function get_user_block_list_omits_null_after(): void
    {
        $raw = ['data' => []];
        $expected = new UserBlockListResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users/blocks', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, UserBlockListResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getUserBlockList(new GetUserBlockListRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function block_user_omits_null_context_and_reason(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'users/blocks', $this->token, ['target_user_id' => 'target-1'], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->blockUser(new BlockUserRequest(targetUserId: 'target-1'), $this->token);
    }

    #[Test]
    public function block_user_unwraps_context_and_reason_enums(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'users/blocks', $this->token, [
                'target_user_id' => 'target-1',
                'source_context' => 'chat',
                'reason'         => 'harassment',
            ], [])
            ->willReturn([]);

        $this->api()->blockUser(
            new BlockUserRequest(
                targetUserId: 'target-1',
                sourceContext: SourceContext::Chat,
                reason: BlockReason::Harassment,
            ),
            $this->token,
        );
    }

    #[Test]
    public function unblock_user_deletes_query(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'users/blocks', $this->token, ['target_user_id' => 'target-1'])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->unblockUser(new UnblockUserRequest(targetUserId: 'target-1'), $this->token);
    }

    #[Test]
    public function get_user_extensions_sends_no_query(): void
    {
        $raw = ['data' => []];
        $expected = new UserExtensionsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users/extensions/list', $this->token, [])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, UserExtensionsResponse::class)
            ->willReturn($expected);

        $this->assertSame($expected, $this->api()->getUserExtensions($this->token));
    }

    #[Test]
    public function get_user_active_extensions_omits_null_user_id(): void
    {
        $raw = ['data' => []];
        $expected = new UserActiveExtensionsResponse(data: $this->activeExtensions());

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users/extensions', $this->token, [])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, UserActiveExtensionsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getUserActiveExtensions(new GetUserActiveExtensionsRequest(), $this->token),
        );
    }

    #[Test]
    public function get_user_active_extensions_forwards_user_id(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'users/extensions', $this->token, ['user_id' => 'user-1'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(
            new UserActiveExtensionsResponse(data: $this->activeExtensions()),
        );

        $this->api()->getUserActiveExtensions(new GetUserActiveExtensionsRequest(userId: 'user-1'), $this->token);
    }

    #[Test]
    public function update_user_extensions_puts_normalized_payload_without_query(): void
    {
        $extensions = $this->updateUserExtension();
        $normalized = ['data' => ['panel' => ['1' => ['active' => true, 'id' => 'ext-1', 'version' => '1.0']]]];
        $raw = ['data' => []];
        $expected = new UserActiveExtensionsResponse(data: $this->activeExtensions());

        $this->normalizer->expects($this->once())->method('normalize')->with($extensions)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'users/extensions', $this->token, [], $normalized)
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, UserActiveExtensionsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateUserExtensions(
                new UpdateUserExtensionsRequest(extensions: $extensions),
                $this->token,
            ),
        );
    }

    private function updateUserExtension(): UpdateUserExtension
    {
        return new UpdateUserExtension(data: [
            'panel' => [
                '1' => ['active' => true, 'id' => 'ext-1', 'version' => '1.0'],
            ],
        ]);
    }
}
