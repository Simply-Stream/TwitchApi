<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Serialization;

interface NormalizerInterface
{
    /** @return array<string, mixed> */
    public function normalize(object $data): array;
}
