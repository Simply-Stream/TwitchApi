<?php

namespace SimplyStream\TwitchApi\Helix\Models\Clip;

final readonly class ClipDownload {
    /**
     * @param string      $clipId               An ID that uniquely identifies the clip.
     * @param string|null $landscapeDownloadUrl The landscape URL to download the clip. This field is null if the URL is not available.
     * @param string|null $portraitDownloadUrl  The portrait URL to download the clip. This field is null if the URL is not available.
     */
    public function __construct(
        public string $clipId,
        public ?string $landscapeDownloadUrl = null,
        public ?string $portraitDownloadUrl = null,
    ) { }
}
