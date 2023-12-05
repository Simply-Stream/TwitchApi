<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

abstract readonly class AbstractModel implements \JsonSerializable
{
    use SerializesModels;

    public function jsonSerialize(): mixed
    {
        // Not sure if this will stay like this, null values could possibly be wanted
        return array_filter($this->toArray(), fn ($value) => null !== $value);
    }
}
