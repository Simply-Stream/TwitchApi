<?php

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\EntitlementsApi;

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
    }
}
