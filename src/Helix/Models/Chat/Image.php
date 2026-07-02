<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Image
{
    use SerializesModels;

    /**
     * @param string $url_1x The URL to a small version of the image.
     * @param string $url_2x The URL to a medium version of the image.
     * @param string $url_4x The URL to a large version of the image.
     */
    public function __construct(
        private string $url_1x,
        private string $url_2x,
        private string $url_4x,
    ) {
    }

    public function getUrl1x(): string
    {
        return $this->url_1x;
    }

    public function getUrl2x(): string
    {
        return $this->url_2x;
    }

    public function getUrl4x(): string
    {
        return $this->url_4x;
    }
}
