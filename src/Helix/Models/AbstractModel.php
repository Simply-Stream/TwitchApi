<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

abstract readonly class AbstractModel implements \JsonSerializable
{
    use SerializesModels;

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
