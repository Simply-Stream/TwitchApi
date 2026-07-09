<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\FulfillmentStatus;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Request\GetDropsEntitlementsRequest;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Request\UpdateDropsEntitlementsRequest;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Response\DropsEntitlementsResponse;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Response\UpdateDropsEntitlementsResponse;
use SimplyStream\TwitchApi\Helix\Api\EntitlementsApi;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlement;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlementUpdate;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\UpdateDropEntitlement;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsEntitlementsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(EntitlementsApi::class)]
final class EntitlementsApiTest extends TestCase
{
    use BuildsEntitlementsApi;

    #[Test]
    public function get_drops_entitlements_denormalizes_both_timestamps(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'                 => 'fb78259e-fb81-4d1b-8333-34a06ffc24c0',
                'benefit_id'         => '74c52265-e214-48a6-91b9-23b6014e8041',
                'timestamp'          => '2019-01-28T04:17:53.325Z',
                'user_id'            => '25009227',
                'game_id'            => '33214',
                'fulfillment_status' => 'CLAIMED',
                'last_updated'       => '2019-01-28T04:17:53.325Z',
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getDropsEntitlements(
            new GetDropsEntitlementsRequest(),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/entitlements/drops', $request->getUri()->getPath());
        $this->assertSame('first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(DropsEntitlementsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $entitlement = $response->data[0];
        $this->assertInstanceOf(DropEntitlement::class, $entitlement);
        $this->assertSame('fb78259e-fb81-4d1b-8333-34a06ffc24c0', $entitlement->id);
        $this->assertSame('CLAIMED', $entitlement->fulfillmentStatus);
        $this->assertInstanceOf(DateTimeInterface::class, $entitlement->timestamp);
        $this->assertInstanceOf(DateTimeInterface::class, $entitlement->lastUpdated);
    }

    #[Test]
    public function get_drops_entitlements_repeats_ids_and_unwraps_the_status_enum(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getDropsEntitlements(
            new GetDropsEntitlementsRequest(
                ids: ['ent-1', 'ent-2'],
                userId: '25009227',
                gameId: '33214',
                fulfillmentStatus: FulfillmentStatus::Fulfilled,
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'id=ent-1&id=ent-2&user_id=25009227&game_id=33214&fulfillment_status=FULFILLED&first=20',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function update_drops_entitlements_sends_a_body_without_a_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                [
                    'status' => 'SUCCESS',
                    'ids'    => ['fb78259e-fb81-4d1b-8333-34a06ffc24c0'],
                ],
                [
                    'status' => 'UNAUTHORIZED',
                    'ids'    => ['8ee2e12a-3e5e-4d21-a0b9-9dfa1a1e3f2c'],
                ],
            ],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->updateDropsEntitlements(
            new UpdateDropsEntitlementsRequest(
                entitlement: new UpdateDropEntitlement(
                    entitlementIds: ['fb78259e-fb81-4d1b-8333-34a06ffc24c0'],
                    fulfillmentStatus: 'FULFILLED',
                ),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame('/helix/entitlements/drops', $request->getUri()->getPath());
        $this->assertSame('', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame([
            'entitlement_ids'    => ['fb78259e-fb81-4d1b-8333-34a06ffc24c0'],
            'fulfillment_status' => 'FULFILLED',
        ], $body);

        $this->assertInstanceOf(UpdateDropsEntitlementsResponse::class, $response);
        $this->assertCount(2, $response->data);

        $update = $response->data[0];
        $this->assertInstanceOf(DropEntitlementUpdate::class, $update);
        $this->assertSame('SUCCESS', $update->status);
        $this->assertSame(['fb78259e-fb81-4d1b-8333-34a06ffc24c0'], $update->ids);

        $this->assertSame('UNAUTHORIZED', $response->data[1]->status);
    }

    #[Test]
    public function update_drops_entitlements_omits_a_null_fulfillment_status(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateDropsEntitlements(
            new UpdateDropsEntitlementsRequest(
                entitlement: new UpdateDropEntitlement(entitlementIds: ['ent-1']),
            ),
            new StaticAccessToken(),
        );

        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);

        // SKIP_NULL_VALUES keeps the sparse update sparse.
        $this->assertSame(['entitlement_ids' => ['ent-1']], $body);
    }
}
