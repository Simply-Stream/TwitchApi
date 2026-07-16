<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

interface ApiClientInterface
{
    /**
     * @param array<string, scalar|array<scalar>> $query
     * @param array<string, mixed>|null $body
     * @return array<string, mixed>
     */
    public function request(
        string $method,
        string $path,
        AccessTokenInterface $accessToken,
        array $query = [],
        ?array $body = null,
    ): array;

    /**
     * @param array<string, scalar|array<scalar>> $query
     */
    public function requestICalendar(
        string $path,
        array $query = [],
    ): string;
}
