<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApiBundle\Helix\Models\AbstractModel;
use SimplyStream\TwitchApiBundle\Helix\Models\TwitchResponseInterface;

/**
 * @template T
 */
interface ApiClientInterface
{
    /**
     * @param string                    $path
     * @param array                     $query
     * @param string|null               $type
     * @param string                    $method
     * @param AbstractModel|null        $body
     * @param AccessTokenInterface|null $accessToken
     *
     * @return T|null
     */
    public function sendRequest(
        string $path,
        array $query,
        string $type = null,
        string $method = 'GET',
        ?AbstractModel $body = null,
        ?AccessTokenInterface $accessToken = null
    ): ?TwitchResponseInterface;
}
