<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final readonly class ApiClient implements ApiClientInterface
{
    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private string $clientId,
        private string $baseUrl = 'https://api.twitch.tv/helix',
    ) {
    }

    public function request(
        string $method,
        string $path,
        AccessTokenInterface $accessToken,
        array $query = [],
        ?array $body = null,
    ): array {
        $uri = $this->baseUrl . '/' . ltrim($path, '/');
        if ($query !== []) {
            $uri .= '?' . $this->buildQuery($query);
        }

        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Client-Id', $this->clientId)
            ->withHeader('Authorization', 'Bearer ' . $accessToken->getAccessToken())
            ->withHeader('Accept', 'application/json');

        if ($body !== null) {
            $request = $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->streamFactory->createStream(
                    json_encode($body, JSON_THROW_ON_ERROR),
                ));
        }

        $response = $this->httpClient->sendRequest($request);
        $status = $response->getStatusCode();
        $raw = (string) $response->getBody();

        if ($status >= 400) {
            throw TwitchApiException::fromResponse($status, $raw);
        }

        if ($status === 204 || $raw === '') {
            return [];
        }

        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }

    public function requestICalendar(string $path, array $query = []): string
    {
        $uri = $this->baseUrl . '/' . ltrim($path, '/');
        if ($query !== []) {
            $uri .= '?' . $this->buildQuery($query);
        }

        $request = $this->requestFactory->createRequest('GET', $uri)
            ->withHeader('Client-Id', $this->clientId)
            ->withHeader('Accept', 'text/calendar');

        $response = $this->httpClient->sendRequest($request);
        $status = $response->getStatusCode();
        $raw = (string) $response->getBody();

        if ($status >= 400) {
            throw TwitchApiException::fromResponse($status, $raw);
        }

        return $raw;
    }

    /**
     * Twitch expects repeated keys (user_id=a&user_id=b),
     * not http_build_query's user_id[0]=a.
     *
     * @param array<string, scalar|array<scalar>> $query
     */
    private function buildQuery(array $query): string
    {
        $parts = [];
        foreach ($query as $key => $value) {
            foreach ((array) $value as $item) {
                $parts[] = rawurlencode($key) . '=' . rawurlencode($this->stringifyQueryValue($item));
            }
        }

        return implode('&', $parts);
    }

    private function stringifyQueryValue(mixed $value): string
    {
        // Twitch expects literal "true"/"false"; (string) bool would yield "1"/"".
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
