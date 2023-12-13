<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use CuyZ\Valinor\Mapper\MappingError;
use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use SimplyStream\TwitchApi\Helix\Models\TwitchResponseInterface;

/**
 * @template T of TwitchResponseInterface
 */
abstract class AbstractApi
{
    public function __construct(protected ApiClient $apiClient)
    {
    }

    /**
     * @param string                    $path
     * @param array                     $query
     * @param string|null               $type
     * @param string                    $method
     * @param array                     $headers
     * @param AbstractModel|null        $body
     * @param AccessTokenInterface|null $accessToken
     *
     * @return T|null
     * @throws MappingError
     * @throws JsonException
     */
    protected function sendRequest(
        string $path,
        array $query = [],
        string $type = null,
        string $method = 'GET',
        array $headers = [],
        ?AbstractModel $body = null,
        ?AccessTokenInterface $accessToken = null
    ): ?TwitchResponseInterface {
        return $this->apiClient->sendRequest($path, $query, $type, $method, $body, $accessToken, $headers);
    }
}
