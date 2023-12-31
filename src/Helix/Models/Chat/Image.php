<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Image
{
    use SerializesModels;

    /**
     * @param string $url1x The URL to a small version of the image.
     * @param string $url2x The URL to a medium version of the image.
     * @param string $url4x The URL to a large version of the image.
     */
    public function __construct(
        private string $url1x,
        private string $url2x,
        private string $url4x,
    ) {
    }

    public function getUrl1x(): string
    {
        return $this->url1x;
    }

    public function getUrl2x(): string
    {
        return $this->url2x;
    }

    public function getUrl4x(): string
    {
        return $this->url4x;
    }
}
