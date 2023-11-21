<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

/**
 * @template T
 */
interface TwitchResponseInterface
{
    /**
     * @return T
     */
    public function getData(): mixed;
}
