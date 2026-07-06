<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class Image
{
    /**
     * @param string $url_1x The URL to a small version of the image.
     * @param string $url_2x The URL to a medium version of the image.
     * @param string $url_4x The URL to a large version of the image.
     */
    public function __construct(
        public string $url_1x,
        public string $url_2x,
        public string $url_4x,
    ) {
    }
}
