<?php

namespace SimplyStream\TwitchApiBundle\Tests\Functional;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Nyholm\Psr7\Request;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApiBundle\Helix\Authentication\Provider\TwitchProvider;

class FunctionalTestCase extends TestCase
{
    protected array $clients;
    protected array $appAccessToken;
    protected array $users;

    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    protected function setUp(): void {
        parent::setUp();
        $client = new Client();
        $this->users = json_decode($client->send(new Request('GET', 'http://localhost:8000/units/users'))->getBody(), true, 512, JSON_THROW_ON_ERROR)['data'];
        $this->clients = json_decode($client->send(new Request('GET', 'http://localhost:8000/units/clients'))->getBody(), true, 512, JSON_THROW_ON_ERROR)['data'][0];
        $this->appAccessToken = json_decode($client->sendRequest((new Request('POST', 'http://localhost:8000/auth/token?' . http_build_query([
                'client_id' => $this->clients['ID'],
                'client_secret' => $this->clients['Secret'],
                'grant_type' => 'client_credentials',
            ])))
        )->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function createTwitchProvider() {
        return new TwitchProvider([
            'clientId' => $this->clients['ID'],
            'clientSecret' => $this->clients['Secret'],
            'urlAuthorize' => 'https://id.twitch.tv/oauth2/authorize',
            'urlAccessToken' => 'https://id.twitch.tv/oauth2/token',
            'urlResourceOwnerDetails' => 'https://id.twitch.tv/oauth2/userinfo',
            'redirectUri' => 'http://localhost/check/twitch',
            'scopes' => '',
        ]);
    }

    protected function getAccessTokenForUser(string $userId, array $scopes = []) {
        $client = new Client();

        return json_decode($client->sendRequest((new Request('POST', 'http://localhost:8000/auth/authorize?' . http_build_query([
                'client_id' => $this->clients['ID'],
                'client_secret' => $this->clients['Secret'],
                'grant_type' => 'user_token',
                'user_id' => $userId,
                'scope' => implode(' ', $scopes)
            ])))
        )->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
