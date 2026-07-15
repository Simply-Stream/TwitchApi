<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\CreateExtensionSecretRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionBitsProductsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionConfigurationSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionLiveChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionSecretsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetReleasedExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionBitsProductsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionConfigurationSegmentsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionLiveChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionSecretsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionsResponse;
use SimplyStream\TwitchApi\Helix\Api\ExtensionsApi;
use SimplyStream\TwitchApi\Helix\Models\Extensions\Extension;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsExtensionsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ExtensionsApi::class)]
final class ExtensionsApiTest extends TestCase
{
    use BuildsExtensionsApi;

    #[Test]
    public function get_extension_configuration_segment_repeats_the_segments(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'segment'        => 'broadcaster',
                'broadcaster_id' => '141981764',
                'content'        => '{"foo":"bar"}',
                'version'        => '0.0.1',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensionConfigurationSegment(
            new GetExtensionConfigurationSegmentRequest(
                extensionId: 'ext-1',
                segments: ['broadcaster', 'developer'],
                broadcasterId: '141981764',
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/extensions/configurations', $request->getUri()->getPath());
        $this->assertSame(
            'extension_id=ext-1&segment=broadcaster&segment=developer&broadcaster_id=141981764',
            $request->getUri()->getQuery(),
        );

        // JWT and OAuth tokens travel the same way: Bearer plus Client-Id.
        $this->assertSame('Bearer test-token', $request->getHeaderLine('Authorization'));
        $this->assertSame('client-id', $request->getHeaderLine('Client-Id'));

        $this->assertInstanceOf(ExtensionConfigurationSegmentsResponse::class, $response);
        $this->assertSame('broadcaster', $response->data[0]->segment);
    }

    #[Test]
    public function get_extension_configuration_segment_omits_a_null_broadcaster_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getExtensionConfigurationSegment(
            new GetExtensionConfigurationSegmentRequest(extensionId: 'ext-1', segments: ['global']),
            new StaticAccessToken(),
        );

        // broadcaster_id must be absent for the global segment.
        $this->assertSame(
            'extension_id=ext-1&segment=global',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function get_extension_live_channels_paginates(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'   => '141981764',
                'broadcaster_name' => 'TwitchDev',
                'game_name'        => 'Science & Technology',
                'game_id'          => '509670',
                'title'            => 'TwitchDev Monthly Update',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensionLiveChannels(
            new GetExtensionLiveChannelsRequest(extensionId: 'ext-1', first: 50, after: 'after-cursor'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/extensions/live', $request->getUri()->getPath());
        $this->assertSame('extension_id=ext-1&first=50&after=after-cursor', $request->getUri()->getQuery());

        $this->assertInstanceOf(ExtensionLiveChannelsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);
        $this->assertSame('TwitchDev', $response->data[0]->broadcasterName);
    }

    #[Test]
    public function get_extension_secrets_sends_only_the_extension_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'format_version' => 1,
                'secrets'        => [[
                    'content'    => 'secret-content',
                    'active_at'  => '2021-03-10T15:04:21Z',
                    'expires_at' => '2021-03-11T15:04:21Z',
                ]],
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensionSecrets(
            new GetExtensionSecretsRequest(extensionId: 'ext-1'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/extensions/jwt/secrets', $request->getUri()->getPath());
        $this->assertSame('extension_id=ext-1', $request->getUri()->getQuery());

        $this->assertInstanceOf(ExtensionSecretsResponse::class, $response);
        $this->assertCount(1, $response->data);
    }

    #[Test]
    public function create_extension_secret_posts_with_the_delay_as_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'format_version' => 1,
                'secrets'        => [],
            ]],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->createExtensionSecret(
            new CreateExtensionSecretRequest(extensionId: 'ext-1', delay: 600),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('extension_id=ext-1&delay=600', $request->getUri()->getQuery());
    }

    #[Test]
    public function get_extensions_denormalizes_an_extension(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->extensionPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensions(
            new GetExtensionsRequest(extensionId: 'ext-1', extensionVersion: '1.0.0'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/extensions', $request->getUri()->getPath());
        $this->assertSame('extension_id=ext-1&extension_version=1.0.0', $request->getUri()->getQuery());

        $this->assertInstanceOf(ExtensionsResponse::class, $response);

        $extension = $response->data[0];
        $this->assertInstanceOf(Extension::class, $extension);
        $this->assertSame('Twitch', $extension->authorName);
        $this->assertTrue($extension->bitsEnabled);
        $this->assertSame('Released', $extension->state);

        // icon_urls is a dictionary keyed by size, not a list.
        $this->assertSame('https://cdn.jtvnw.net/icon-100.png', $extension->iconUrls['100x100']);
    }

    #[Test]
    public function get_extensions_omits_a_null_version(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getExtensions(
            new GetExtensionsRequest(extensionId: 'ext-1'),
            new StaticAccessToken(),
        );

        $this->assertSame('extension_id=ext-1', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function get_released_extensions_uses_the_released_path(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->extensionPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getReleasedExtensions(
            new GetReleasedExtensionsRequest(extensionId: 'ext-1'),
            new StaticAccessToken(),
        );

        $this->assertSame('/helix/extensions/released', $http->getLastRequest()->getUri()->getPath());
        $this->assertInstanceOf(ExtensionsResponse::class, $response);
    }

    #[Test]
    public function get_extension_bits_products_sends_the_bool_as_a_literal(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'sku'            => 'test-sku',
                'cost'           => ['amount' => 990, 'type' => 'bits'],
                'in_development' => true,
                'display_name'   => 'Test Product',
                'expiration'     => '',
                'is_broadcast'   => true,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensionBitsProducts(
            new GetExtensionBitsProductsRequest(shouldIncludeAll: true),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();

        // The bits products live outside /extensions.
        $this->assertSame('/helix/bits/extensions', $request->getUri()->getPath());
        $this->assertSame('should_include_all=true', $request->getUri()->getQuery());

        $this->assertInstanceOf(ExtensionBitsProductsResponse::class, $response);
        $this->assertSame('test-sku', $response->data[0]->sku);
    }

    #[Test]
    public function get_extension_bits_products_sends_false_as_a_literal(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getExtensionBitsProducts(
            new GetExtensionBitsProductsRequest(),
            new StaticAccessToken(),
        );

        // false must survive as a literal, not be filtered out.
        $this->assertSame('should_include_all=false', $http->getLastRequest()->getUri()->getQuery());
    }

    /** @return array<string, mixed> */
    private function extensionPayload(): array
    {
        return [
            'author_name'                 => 'Twitch',
            'bits_enabled'                => true,
            'can_install'                 => false,
            'configuration_location'      => 'hosted',
            'description'                 => 'Test extension',
            'eula_tos_url'                => 'https://www.twitch.tv/tos',
            'has_chat_support'            => false,
            'icon_url'                    => 'https://cdn.jtvnw.net/icon.png',
            'icon_urls'                   => ['100x100' => 'https://cdn.jtvnw.net/icon-100.png'],
            'id'                          => 'ext-1',
            'name'                        => 'Test Extension',
            'privacy_policy_url'          => 'https://www.twitch.tv/privacy',
            'request_identity_link'       => false,
            'screenshot_urls'             => ['https://cdn.jtvnw.net/screenshot.png'],
            'state'                       => 'Released',
            'subscriptions_support_level' => 'none',
            'summary'                     => 'A test extension',
            'support_email'               => 'support@example.com',
            'version'                     => '1.0.0',
            'viewer_summary'              => 'Viewer summary',
            'views'                       => ['mobile' => ['viewer_url' => 'https://example.com/mobile']],
            'allowlisted_config_urls'     => [],
            'allowlisted_panel_urls'      => [],
        ];
    }

    #[Test]
    public function get_extension_configuration_segment_handles_a_global_segment(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'segment' => 'global',
                'content' => '{"foo":"bar"}',
                'version' => '0.0.1',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensionConfigurationSegment(
            new GetExtensionConfigurationSegmentRequest(extensionId: 'ext-1', segments: ['global']),
            new StaticAccessToken(),
        );

        // Twitch omits broadcaster_id entirely for the global segment.
        $this->assertNull($response->data[0]->broadcasterId);
        $this->assertSame('global', $response->data[0]->segment);
    }
}
