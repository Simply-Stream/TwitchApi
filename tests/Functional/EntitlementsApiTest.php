<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\EntitlementsApi;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlement;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlementUpdate;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\UpdateDropEntitlementRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class EntitlementsApiTest extends UserAwareFunctionalTestCase
{
    public function testGetDropsEntitlements()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $entitlementsApi = new EntitlementsApi($apiClient);
        $getDropsEntitlementsResponse = $entitlementsApi->getDropsEntitlements(
            new AccessToken($this->getAccessTokenForUser($testUser['id']))
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getDropsEntitlementsResponse);
        $this->assertCount(1, $getDropsEntitlementsResponse->getData());
        $this->assertContainsOnlyInstancesOf(DropEntitlement::class, $getDropsEntitlementsResponse->getData());

        foreach ($getDropsEntitlementsResponse->getData() as $dropEntitlement) {
            $this->assertIsString($dropEntitlement->getId());
            $this->assertNotEmpty($dropEntitlement->getId());
            $this->assertIsString($dropEntitlement->getBenefitId());
            $this->assertNotEmpty($dropEntitlement->getBenefitId());
            $this->assertIsString($dropEntitlement->getUserId());
            $this->assertNotEmpty($dropEntitlement->getUserId());
            $this->assertIsString($dropEntitlement->getGameId());
            $this->assertNotEmpty($dropEntitlement->getGameId());
            $this->assertIsString($dropEntitlement->getFulfillmentStatus());
            $this->assertNotEmpty($dropEntitlement->getFulfillmentStatus());
            $this->assertContains($dropEntitlement->getFulfillmentStatus(), ['CLAIMED', 'FULFILLED']);
        }
    }

    public function testUpdateDropsEntitlements()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $entitlementsApi = new EntitlementsApi($apiClient);
        $getDropsEntitlementsResponse = $entitlementsApi->getDropsEntitlements(
            new AccessToken($this->getAccessTokenForUser($testUser['id']))
        );
        $dropEntitlement = $getDropsEntitlementsResponse->getData()[0];

        $updateDropEntitlementsResponse = $entitlementsApi->updateDropsEntitlements(
            new UpdateDropEntitlementRequest([$dropEntitlement->getId()], 'FULFILLED'),
            new AccessToken($this->getAccessTokenForUser($testUser['id']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $updateDropEntitlementsResponse);
        $this->assertCount(1, $updateDropEntitlementsResponse->getData());
        $this->assertContainsOnlyInstancesOf(DropEntitlementUpdate::class, $updateDropEntitlementsResponse->getData());

        foreach ($updateDropEntitlementsResponse->getData() as $dropEntitlementUpdate) {
            $this->assertSame('SUCCESS', $dropEntitlementUpdate->getStatus());
            $this->assertContainsOnly('string', $dropEntitlementUpdate->getIds());
        }
    }
}
