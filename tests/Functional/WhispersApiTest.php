<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\WhispersApi;
use SimplyStream\TwitchApi\Helix\Models\Whispers\SendWhisperRequest;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class WhispersApiTest extends UserAwareFunctionalTestCase
{
    public function testSendWhisper()
    {
        $this->expectNotToPerformAssertions();

        $testUser = $this->users[0];
        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['user:manage:whispers']));
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

        $whispersApi = new WhispersApi($apiClient);
        $whispersApi->sendWhisper(
            $testUser['id'],
            $this->users[1]['id'],
            new SendWhisperRequest('Whisper message!'),
            $accessToken
        );
    }
}
