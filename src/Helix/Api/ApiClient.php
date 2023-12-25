<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Object\DynamicConstructor;
use CuyZ\Valinor\Mapper\Source\Exception\InvalidSource;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\Mapper\Tree\Message\Messages;
use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use SimplyStream\TwitchApi\Helix\Exceptions\BadRequestResponseException;
use SimplyStream\TwitchApi\Helix\Exceptions\ForbiddenResponseException;
use SimplyStream\TwitchApi\Helix\Exceptions\InternalServerErrorResponseException;
use SimplyStream\TwitchApi\Helix\Exceptions\NotFoundResponseException;
use SimplyStream\TwitchApi\Helix\Exceptions\TooManyRequestsResponseException;
use SimplyStream\TwitchApi\Helix\Exceptions\UnauthorizedResponseException;
use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions\Subscriptions;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchResponseInterface;
use Stringable;

use function json_decode;
use function json_encode;

class ApiClient implements ApiClientInterface
{
    use LoggerTrait;
    use LoggerAwareTrait;

    protected string $baseUrl = 'https://api.twitch.tv/helix/';

    /**
     * @param ClientInterface                                $client
     * @param RequestFactoryInterface&StreamFactoryInterface $requestFactory
     * @param MapperBuilder                                  $mapperBuilder
     * @param UriFactoryInterface                            $uriFactory
     * @param array|null                                     $options
     */
    public function __construct(
        protected ClientInterface $client,
        protected RequestFactoryInterface&StreamFactoryInterface $requestFactory,
        protected MapperBuilder $mapperBuilder,
        protected UriFactoryInterface $uriFactory,
        protected ?array $options = null
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * @param string                    $path
     * @param array                     $query
     * @param string|null               $type
     * @param string                    $method
     * @param AbstractModel|null        $body
     * @param AccessTokenInterface|null $accessToken
     * @param array                     $headers
     *
     * @return TwitchResponseInterface|null
     * @throws MappingError
     * @throws InvalidSource
     * @throws \JsonException
     * @throws ClientExceptionInterface
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
        $uri = $this->uriFactory->createUri($this->getBaseUrl() . $path)
            ->withQuery($this->buildQueryString(array_filter($query)));
        $request = $this->requestFactory->createRequest($method, $uri);

        if ($body) {
            $request = $request->withBody(
                $this->requestFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR))
            );
        }

        $request = $request
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Client-ID', $this->options['clientId']);

        if ($accessToken) {
            $request = $request->withHeader(
                'Authorization',
                ucfirst($accessToken->getValues()['token_type']) . ' ' . $accessToken->getToken()
            );
        }

        if ($headers) {
            foreach ($headers as $header => $value) {
                $request = $request->withHeader($header, $value);
            }
        }

        $response = $this->client->sendRequest($request);

        // @TODO: Not sure if this will stay like this, but for now it's ok
        // @TODO: Also think about using match instead of switch
        switch ($response->getStatusCode()) {
            case 200:
                $responseContent = $response->getBody()->getContents();

                // @TODO: For now, this is ok, but might be changed in the future. Or maybe completely discarded
                if ($response->getHeader('Content-Type')[0] === 'text/calendar') {
                    return new TwitchDataResponse($responseContent);
                }

                try {
                    $source = Source::json($responseContent);

                    return $this->mapperBuilder
                        ->registerConstructor(fn (string $time): DateTimeImmutable => new DateTimeImmutable($time))
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
            case 204:
                return null;
            case 400:
                $responseContent = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                throw new BadRequestResponseException(
                    $request,
                    $responseContent['data'],
                    $responseContent['data'][0]['message']
                );
            case 401:
                $responseContent = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                throw new UnauthorizedResponseException(
                    $request,
                    $responseContent['data'],
                    $responseContent['data'][0]['message']
                );
            case 403:
                $responseContent = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                throw new ForbiddenResponseException(
                    $request,
                    $responseContent['data'],
                    $responseContent['data'][0]['message']
                );
            case 404:
                $responseContent = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                throw new NotFoundResponseException(
                    $request,
                    $responseContent['data'],
                    $responseContent['data'][0]['message']
                );
            case 429:
                $responseContent = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                throw new TooManyRequestsResponseException(
                    $request,
                    $responseContent['data'],
                    $responseContent['data'][0]['message']
                );
            case 500:
                $responseContent = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                throw new InternalServerErrorResponseException(
                    $request,
                    $responseContent['data'],
                    $responseContent['data'][0]['message']
                );
            default:
                throw new \RuntimeException('Unknown status returned from Twitch');
        }
    }

    /**
     * Returns the current baseUrl set for the twitch endpoint
     *
     * @return string
     */
    private function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * This function will build the query string the way twitch requires it.
     * Twitch wants a format like this: ?login=user_login&login=another_user&login=someone_else
     * Due to the fact, that it's not how the standard works, http_build_query won't work here.
     *
     * @param array           $query
     * @param string|int|null $prefix
     *
     * @return string
     */
    private function buildQueryString(array $query, string|int|null $prefix = null): string
    {
        $queryString = '';

        foreach ($query as $key => $value) {
            if ($prefix !== null) {
                $key = $prefix;
            }

            if (is_array($value)) {
                $queryString .= $this->buildQueryString($value, $key);
            } else {
                $queryString .= urlencode((string)$key) . '=' . urlencode((string)$value) . '&';
            }
        }

        return rtrim($queryString, '&');
    }

    /**
     * {@inheritDoc}
     */
    private function log($level, Stringable|string $message, array $context = []): void
    {
        $this->logger?->log($level, $message, $context);
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
}
