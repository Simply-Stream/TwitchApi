<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

/**
 * @template T
 */
readonly class TwitchDataResponse implements TwitchResponseInterface
{
    use SerializesModels;

    /**
     * @param T $data
     */
    public function __construct(
        protected mixed $data
    ) {
    }

    /**
     * @return T
     * @TODO: Replace arrays by Collections with iterable interface. With that we'll have some nice helper functions like
     *        $collection->first(), ->each(), etc.
     */
    public function getData(): mixed
    {
        return $this->data;
    }
}
