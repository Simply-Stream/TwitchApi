<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\FulfillmentStatus;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Request\GetDropsEntitlementsRequest;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Request\UpdateDropsEntitlementsRequest;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Response\DropsEntitlementsResponse;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Response\UpdateDropsEntitlementsResponse;
use SimplyStream\TwitchApi\Helix\Api\EntitlementsApi;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\UpdateDropEntitlement;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(EntitlementsApi::class)]
final class EntitlementsApiTest extends TestCase
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

    private function api(): EntitlementsApi
    {
        return new EntitlementsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_drops_entitlements_omits_empty_and_null(): void
    {
        $raw = ['data' => []];
        $expected = new DropsEntitlementsResponse(data: []);

        // ids defaults to [] -> filtered; userId/gameId/status/after null -> filtered; first stays.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'entitlements/drops', $this->token, ['first' => 20])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, DropsEntitlementsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getDropsEntitlements(new GetDropsEntitlementsRequest(), $this->token),
        );
    }

    #[Test]
    public function get_drops_entitlements_repeats_ids_and_unwraps_status_enum(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'entitlements/drops', $this->token, [
                'id'                 => ['e1', 'e2'],
                'user_id'            => 'user-1',
                'game_id'            => 'game-1',
                'fulfillment_status' => 'CLAIMED',
                'first'              => 50,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new DropsEntitlementsResponse(data: []));

        $this->api()->getDropsEntitlements(
            new GetDropsEntitlementsRequest(
                ids: ['e1', 'e2'],
                userId: 'user-1',
                gameId: 'game-1',
                fulfillmentStatus: FulfillmentStatus::Claimed,
                first: 50,
            ),
            $this->token,
        );
    }

    #[Test]
    public function update_drops_entitlements_patches_normalized_payload(): void
    {
        $entitlement = new UpdateDropEntitlement();
        $normalized = ['entitlement_ids' => ['e1'], 'fulfillment_status' => 'FULFILLED'];
        $raw = ['data' => []];
        $expected = new UpdateDropsEntitlementsResponse(data: []);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($entitlement)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'entitlements/drops', $this->token, [], $normalized)
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, UpdateDropsEntitlementsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateDropsEntitlements(
                new UpdateDropsEntitlementsRequest(entitlement: $entitlement),
                $this->token,
            ),
        );
    }
}
