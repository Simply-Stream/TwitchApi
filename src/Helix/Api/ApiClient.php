<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Api;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Object\DynamicConstructor;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\Mapper\Tree\Message\Messages;
use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use InvalidArgumentException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use SimplyStream\TwitchApiBundle\Helix\Authentication\Provider\TwitchProvider;
use SimplyStream\TwitchApiBundle\Helix\Authentication\Token\Storage\InMemoryStorage;
use SimplyStream\TwitchApiBundle\Helix\Authentication\Token\Storage\TokenStorageInterface;
use SimplyStream\TwitchApiBundle\Helix\EventSub\Exceptions\InvalidAccessTokenException;
use SimplyStream\TwitchApiBundle\Helix\Models\AbstractModel;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Subscriptions\Subscriptions;
use SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Transport;
use SimplyStream\TwitchApiBundle\Helix\Models\TwitchResponseInterface;
use Stringable;

use function json_decode;
use function json_encode;

class ApiClient implements ApiClientInterface
{
    use LoggerTrait;
    use LoggerAwareTrait;

    protected string $baseUrl = 'https://api.twitch.tv/helix/';

    /**
     * @param ClientInterface         $client
     * @param RequestFactoryInterface $requestFactory
     * @param TwitchProvider          $twitch
     * @param MapperBuilder           $mapperBuilder
     * @param UriFactoryInterface     $uriFactory
     * @param array|null              $options
     * @param TokenStorageInterface   $tokenStorage
     */
    public function __construct(
        protected ClientInterface $client,
        protected RequestFactoryInterface $requestFactory,
        protected TwitchProvider $twitch,
        protected MapperBuilder $mapperBuilder,
        protected UriFactoryInterface $uriFactory,
        protected ?array $options = null,
        protected TokenStorageInterface $tokenStorage = new InMemoryStorage()
    ) {
        if (!empty($this->options['token'])) {
            foreach ($this->options['token'] as $grant => $token) {
                $this->tokenStorage->save(
                    $grant,
                    new AccessToken([
                        'access_token' => $token['token'],
                        'expires_in' => $token['expires_in'],
                        'token_type' => $token['token_type'],
                    ])
                );
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function sendRequest(
        string $path,
        array $query,
        string $type = null,
        string $method = 'GET',
        ?AbstractModel $body = null,
        ?AccessTokenInterface $accessToken = null,
        array $headers = []
    ): ?TwitchResponseInterface {
        if (!$accessToken) {
            $accessToken = $this->getAccessToken('client_credentials');
        }

        $uri = $this->uriFactory->createUri($this->getBaseUrl() . $path)
            ->withQuery($this->buildQueryString(array_filter($query)));
        $request = $this->requestFactory->createRequest($method, $uri);

        if ($body) {
            $request->withBody($this->requestFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR)));
        }

        $request = $request
            ->withHeader(
                'Authorization',
                ucfirst($accessToken->getValues()['token_type']) . ' ' . $accessToken->getToken()
            )
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Client-ID', $this->options['clientId']);

        if ($headers) {
            foreach ($headers as $header => $value) {
                $request = $request->withHeader($header, $value);
            }
        }

        $response = $this->client->sendRequest($request);
        $responseContent = $response->getBody()->getContents();

        if ($response->getStatusCode() >= 400) {
            // @TODO: Change into ErrorResponse
            $error = json_decode($responseContent, false, 512, JSON_THROW_ON_ERROR);
            $this->error($error->message, ['response' => $responseContent]);
            throw new InvalidArgumentException(sprintf('Error from API: "(%s): %s"', $error->error, $error->message));
        }

        if ($response->getStatusCode() === 204) {
            return null;
        }

        try {
            $source = Source::json($responseContent);
            return $this->mapperBuilder
                ->registerConstructor(
                    #[DynamicConstructor]
                    function (string $className, array $value): Subscription {
                        $type = Subscriptions::MAP[$value['type']];

                        return new $type(
                            $value['condition'],
                            new Transport(...$value['transport']),
                            $value['id'],
                            $value['status'],
                            new DateTimeImmutable($value['createdAt']),
                        );
                    }
                )
                ->allowPermissiveTypes()
                ->allowSuperfluousKeys()
                ->mapper()->map($type, $source->camelCaseKeys());
        } catch (MappingError $mappingError) {
            $messages = Messages::flattenFromNode($mappingError->node())->errors();
            foreach ($messages as $message) {
                $this->log($message, LogLevel::ERROR);
            }

            throw $mappingError;
        }
    }

    /**
     * @param string $grant
     *
     * @return AccessTokenInterface
     */
    protected function getAccessToken(string $grant): AccessTokenInterface
    {
        if ($this->tokenStorage->has($grant)) {
            return $this->tokenStorage->get($grant);
        }

        $accessToken = null;

        try {
            $accessToken = $this->twitch->getAccessToken($grant);
        } catch (IdentityProviderException $e) {
            throw new InvalidAccessTokenException($accessToken, $e->getMessage());
        }

        return $accessToken;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * This method allows you to change the API endpoint for Twitch.
     * Can be useful to change this to the Twitch mock data server
     *
     * @param string $baseUrl
     *
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * This function will build the query string the way twitch requires it.
     * Twitch wants a format like this: ?login=user_login&login=another_user&login=someone_else
     * Due to the fact, that it's not how the standard works, http_build_query won't work here.
     *
     * @param array           $query
     * @param string|int|null $prefix
     *
     * @return string|null
     */
    private function buildQueryString(array $query, string|int|null $prefix = null)
    {
        $queryString = '';

        foreach ($query as $key => $value) {
            if ($prefix !== null) {
                $key = $prefix;
            }

            if (is_array($value)) {
                $queryString .= $this->buildQueryString($value, $key);
            } else {
                $queryString .= urlencode($key) . '=' . urlencode($value) . '&';
            }
        }

        return rtrim($queryString, '&');
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->logger?->log($level, $message, $context);
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     *
     * @return $this
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): self
    {
        $this->tokenStorage = $tokenStorage;

        return $this;
    }
}
