<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Serialization;

interface DenormalizerInterface
{
    /**
     * @template T of object
     * @param class-string<T> $type
     * @return T
     */
    public function denormalize(array $data, string $type): object;
}
