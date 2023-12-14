<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Nyholm\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

#[CoversNothing]
class UserAwareFunctionalTestCase extends TestCase
{
    protected array $clients;
    protected array $appAccessToken;
    protected array $users;

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws ClientExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();
        $client = new Client();
        $this->users = json_decode(
            (string)$client->send(new Request('GET', 'http://localhost:8000/units/users'))->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        )['data'];
        $this->clients = json_decode(
            (string)$client->send(new Request('GET', 'http://localhost:8000/units/clients'))->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        )['data'][0];
        $this->appAccessToken = json_decode(
            (string)$client->sendRequest(
                (new Request(
                    'POST',
                    'http://localhost:8000/auth/token?' . http_build_query([
                        'client_id' => $this->clients['ID'],
                        'client_secret' => $this->clients['Secret'],
                        'grant_type' => 'client_credentials',
                    ])
                ))
            )->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * @param string $userId
     * @param array  $scopes
     *
     * @return array
     * @throws JsonException
     * @throws ClientExceptionInterface
     */
    protected function getAccessTokenForUser(string $userId, array $scopes = []): array
    {
        $client = new Client();

        return json_decode(
            (string)$client->sendRequest(
                (new Request(
                    'POST',
                    'http://localhost:8000/auth/authorize?' . http_build_query([
                        'client_id' => $this->clients['ID'],
                        'client_secret' => $this->clients['Secret'],
                        'grant_type' => 'user_token',
                        'user_id' => $userId,
                        'scope' => implode(' ', $scopes),
                    ])
                ))
            )->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
