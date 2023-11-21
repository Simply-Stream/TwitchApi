<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

/**
 * @template T
 */
readonly class TwitchDataResponse implements TwitchResponseInterface
{
    /**
     * @param T $data
     */
    public function __construct(
        protected mixed $data
    ) {
    }

    /**
     * @return T
     */
    public function getData(): mixed
    {
        return $this->data;
    }
}
