<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\CreateExtensionSecretRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionBitsProductsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionConfigurationSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionLiveChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionSecretsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetReleasedExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SendExtensionChatMessageRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SendExtensionPubSubMessageRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SetExtensionConfigurationSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SetExtensionRequiredConfigurationRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\UpdateExtensionBitsProductRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionBitsProductsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionConfigurationSegmentsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionLiveChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionSecretsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionsResponse;
use SimplyStream\TwitchApi\Helix\Api\ExtensionsApi;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionBitsAmount;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ExtensionsApi::class)]
final class ExtensionsApiTest extends TestCase
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

    private function api(): ExtensionsApi
    {
        return new ExtensionsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_extension_configuration_segment_forwards_segments_as_a_list(): void
    {
        $raw = ['data' => []];
        $expected = new ExtensionConfigurationSegmentsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/configurations', $this->token, [
                'extension_id'   => 'ext-1',
                'segment'        => ['broadcaster', 'developer'],
                'broadcaster_id' => '1234',
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, ExtensionConfigurationSegmentsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getExtensionConfigurationSegment(
                new GetExtensionConfigurationSegmentRequest(
                    extensionId: 'ext-1',
                    segments: ['broadcaster', 'developer'],
                    broadcasterId: '1234',
                ),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_extension_configuration_segment_omits_a_null_broadcaster_id(): void
    {
        // broadcaster_id must be absent for the global segment.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/configurations', $this->token, [
                'extension_id' => 'ext-1',
                'segment'      => ['global'],
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')
            ->willReturn(new ExtensionConfigurationSegmentsResponse(data: []));

        $this->api()->getExtensionConfigurationSegment(
            new GetExtensionConfigurationSegmentRequest(extensionId: 'ext-1', segments: ['global']),
            $this->token,
        );
    }

    #[Test]
    public function set_extension_configuration_segment_normalizes_the_request(): void
    {
        $request = new SetExtensionConfigurationSegmentRequest(
            extensionId: 'ext-1',
            segment: 'global',
            content: '{"foo":"bar"}',
            version: '0.0.1',
        );
        $normalized = [
            'extension_id' => 'ext-1',
            'segment'      => 'global',
            'content'      => '{"foo":"bar"}',
            'version'      => '0.0.1',
        ];

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($request)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'extensions/configurations', $this->token, [], $normalized)
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->setExtensionConfigurationSegment($request, $this->token);
    }

    #[Test]
    public function set_extension_required_configuration_sends_body_and_query(): void
    {
        $request = new SetExtensionRequiredConfigurationRequest(
            broadcasterId: '1234',
            extensionId: 'ext-1',
            extensionVersion: '1.0.0',
            requiredConfiguration: 'RCS',
        );
        $normalized = [
            'extension_id'           => 'ext-1',
            'extension_version'      => '1.0.0',
            'required_configuration' => 'RCS',
        ];

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($request)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'extensions/required_configuration', $this->token, ['broadcaster_id' => '1234'], $normalized)
            ->willReturn([]);

        $this->api()->setExtensionRequiredConfiguration($request, $this->token);
    }

    #[Test]
    public function send_extension_pub_sub_message_posts_the_normalized_request(): void
    {
        $request = new SendExtensionPubSubMessageRequest(
            target: ['broadcast'],
            message: 'hello',
            broadcasterId: '1234',
        );
        $normalized = [
            'target'         => ['broadcast'],
            'broadcaster_id' => '1234',
            'message'        => 'hello',
        ];

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($request)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'extensions/pubsub', $this->token, [], $normalized)
            ->willReturn([]);

        $this->api()->sendExtensionPubSubMessage($request, $this->token);
    }

    #[Test]
    public function get_extension_live_channels_forwards_pagination(): void
    {
        $expected = new ExtensionLiveChannelsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/live', $this->token, [
                'extension_id' => 'ext-1',
                'first'        => 50,
                'after'        => 'cursor-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getExtensionLiveChannels(
                new GetExtensionLiveChannelsRequest(extensionId: 'ext-1', first: 50, after: 'cursor-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_extension_live_channels_omits_a_null_cursor(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/live', $this->token, [
                'extension_id' => 'ext-1',
                'first'        => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ExtensionLiveChannelsResponse(data: []));

        $this->api()->getExtensionLiveChannels(
            new GetExtensionLiveChannelsRequest(extensionId: 'ext-1'),
            $this->token,
        );
    }

    #[Test]
    public function get_extension_secrets_sends_only_the_extension_id(): void
    {
        $expected = new ExtensionSecretsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/jwt/secrets', $this->token, ['extension_id' => 'ext-1'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getExtensionSecrets(new GetExtensionSecretsRequest(extensionId: 'ext-1'), $this->token),
        );
    }

    #[Test]
    public function create_extension_secret_forwards_the_delay(): void
    {
        $expected = new ExtensionSecretsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'extensions/jwt/secrets', $this->token, [
                'extension_id' => 'ext-1',
                'delay'        => 600,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createExtensionSecret(
                new CreateExtensionSecretRequest(extensionId: 'ext-1', delay: 600),
                $this->token,
            ),
        );
    }

    #[Test]
    public function create_extension_secret_defaults_the_delay_to_300(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'extensions/jwt/secrets', $this->token, [
                'extension_id' => 'ext-1',
                'delay'        => 300,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ExtensionSecretsResponse(data: []));

        $this->api()->createExtensionSecret(new CreateExtensionSecretRequest(extensionId: 'ext-1'), $this->token);
    }

    #[Test]
    public function send_extension_chat_message_sends_body_and_query(): void
    {
        $request = new SendExtensionChatMessageRequest(
            broadcasterId: '1234',
            text: 'hello',
            extensionId: 'ext-1',
            extensionVersion: '1.0.0',
        );
        $normalized = [
            'text'              => 'hello',
            'extension_id'      => 'ext-1',
            'extension_version' => '1.0.0',
        ];

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($request)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'extensions/chat', $this->token, ['broadcaster_id' => '1234'], $normalized)
            ->willReturn([]);

        $this->api()->sendExtensionChatMessage($request, $this->token);
    }

    #[Test]
    public function get_extensions_forwards_the_version(): void
    {
        $expected = new ExtensionsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions', $this->token, [
                'extension_id'      => 'ext-1',
                'extension_version' => '1.0.0',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getExtensions(
                new GetExtensionsRequest(extensionId: 'ext-1', extensionVersion: '1.0.0'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_extensions_omits_a_null_version(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions', $this->token, ['extension_id' => 'ext-1'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ExtensionsResponse(data: []));

        $this->api()->getExtensions(new GetExtensionsRequest(extensionId: 'ext-1'), $this->token);
    }

    #[Test]
    public function get_released_extensions_uses_the_released_path(): void
    {
        $expected = new ExtensionsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/released', $this->token, ['extension_id' => 'ext-1'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getReleasedExtensions(
                new GetReleasedExtensionsRequest(extensionId: 'ext-1'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_extension_bits_products_lives_outside_the_extensions_path(): void
    {
        $expected = new ExtensionBitsProductsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'bits/extensions', $this->token, ['should_include_all' => true])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getExtensionBitsProducts(
                new GetExtensionBitsProductsRequest(shouldIncludeAll: true),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_extension_bits_products_sends_false_explicitly(): void
    {
        // false must survive: it is not filtered out like null would be.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'bits/extensions', $this->token, ['should_include_all' => false])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ExtensionBitsProductsResponse(data: []));

        $this->api()->getExtensionBitsProducts(new GetExtensionBitsProductsRequest(), $this->token);
    }

    #[Test]
    public function update_extension_bits_product_puts_the_normalized_request(): void
    {
        $request = new UpdateExtensionBitsProductRequest(
            sku: 'test-sku',
            cost: new ExtensionBitsAmount(amount: 990, type: 'bits'),
            displayName: 'Test Product',
        );
        $normalized = [
            'sku'          => 'test-sku',
            'cost'         => ['amount' => 990, 'type' => 'bits'],
            'display_name' => 'Test Product',
        ];
        $expected = new ExtensionBitsProductsResponse(data: []);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($request)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PUT', 'bits/extensions', $this->token, [], $normalized)
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateExtensionBitsProduct($request, $this->token),
        );
    }
}
