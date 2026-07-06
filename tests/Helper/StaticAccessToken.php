<?php

namespace SimplyStream\TwitchApi\Tests\Helper;

use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class StaticAccessToken implements AccessTokenInterface
{
    public function __construct(private readonly string $token = 'test-token') {}

    public function getAccessToken(): string
    {
        return $this->token;
    }
}
