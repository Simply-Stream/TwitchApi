<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Authentication;

interface AccessTokenInterface
{
    public function getAccessToken(): string;
}
