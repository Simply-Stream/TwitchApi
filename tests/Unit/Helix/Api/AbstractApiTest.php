<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\AbstractApi;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(AbstractApi::class)]
final class AbstractApiTest extends TestCase
{
    private ApiClientInterface $apiClient;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;
    private AccessTokenInterface $token;
    private object $api;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->token = new StaticAccessToken();

        // Anonymous subclass exposing the protected verbs for testing.
        $this->api = new class ($this->apiClient, $this->denormalizer, $this->normalizer) extends AbstractApi {
            /** @param array<string, mixed> $query */
            public function callGet(string $path, string $type, AccessTokenInterface $token, array $query = []): object
            {
                return $this->get($path, $type, $token, $query);
            }

            /**
             * @param array<string, mixed> $body
             * @param array<string, mixed> $query
             */
            public function callPost(string $path, string $type, AccessTokenInterface $token, array $body = [], array $query = []): object
            {
                return $this->post($path, $type, $token, $body, $query);
            }

            /** @param array<string, mixed> $body */
            public function callPostWithoutResponse(string $path, AccessTokenInterface $token, array $body = [], array $query = []): void
            {
                $this->postWithoutResponse($path, $token, $body, $query);
            }

            public function callPutWithoutResponse(string $path, AccessTokenInterface $token, array $body = [], array $query = []): void
            {
                $this->putWithoutResponse($path, $token, $body, $query);
            }

            public function callPatch(string $path, string $type, AccessTokenInterface $token, array $body = [], array $query = []): object
            {
                return $this->patch($path, $type, $token, $body, $query);
            }

            public function callPatchWithoutResponse(string $path, AccessTokenInterface $token, array $body = [], array $query = []): void
            {
                $this->patchWithoutResponse($path, $token, $body, $query);
            }

            /** @param array<string, mixed> $query */
            public function callDelete(string $path, AccessTokenInterface $token, array $query = []): void
            {
                $this->delete($path, $token, $query);
            }

            public function callDeleteWithResponse(string $path, string $type, AccessTokenInterface $token, array $query = []): object
            {
                return $this->deleteWithResponse($path, $type, $token, $query);
            }
        };
    }

    #[Test]
    public function get_requests_via_client_and_denormalizes_the_response(): void
    {
        $raw = ['data' => [], 'total' => 3];
        $expected = new \stdClass();

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'some/path', $this->token, ['first' => 20])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, \stdClass::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api->callGet('some/path', \stdClass::class, $this->token, ['first' => 20]),
        );
    }

    #[Test]
    public function post_passes_body_and_query_and_denormalizes(): void
    {
        $raw = ['data' => []];
        $expected = new \stdClass();

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'some/path', $this->token, ['q' => '1'], ['field' => 'value'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, \stdClass::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api->callPost('some/path', \stdClass::class, $this->token, ['field' => 'value'], ['q' => '1']),
        );
    }

    #[Test]
    public function post_without_response_does_not_touch_the_denormalizer(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'some/path', $this->token, [], ['field' => 'value'])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api->callPostWithoutResponse('some/path', $this->token, ['field' => 'value']);
    }

    #[Test]
    public function put_without_response_uses_the_put_verb(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'some/path', $this->token, ['q' => '1'], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api->callPutWithoutResponse('some/path', $this->token, [], ['q' => '1']);
    }

    #[Test]
    public function patch_passes_body_and_denormalizes(): void
    {
        $raw = ['data' => []];
        $expected = new \stdClass();

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'some/path', $this->token, [], ['field' => 'value'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, \stdClass::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api->callPatch('some/path', \stdClass::class, $this->token, ['field' => 'value']),
        );
    }

    #[Test]
    public function patch_without_response_uses_the_patch_verb(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'some/path', $this->token, [], ['field' => 'value'])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api->callPatchWithoutResponse('some/path', $this->token, ['field' => 'value']);
    }

    #[Test]
    public function delete_uses_the_delete_verb_and_ignores_the_response(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'some/path', $this->token, ['id' => 'x'])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api->callDelete('some/path', $this->token, ['id' => 'x']);
    }

    #[Test]
    public function delete_with_response_denormalizes(): void
    {
        $raw = ['data' => ['1', '2']];
        $expected = new \stdClass();

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'some/path', $this->token, ['id' => ['1', '2']])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, \stdClass::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api->callDeleteWithResponse('some/path', \stdClass::class, $this->token, ['id' => ['1', '2']]),
        );
    }
}
