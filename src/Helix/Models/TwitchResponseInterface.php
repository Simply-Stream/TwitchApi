<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models;

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
