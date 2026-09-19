<?php

namespace SimplyStream\TwitchApi\Helix\Api\Clips\Response;

use SimplyStream\TwitchApi\Helix\Models\Clip\ClipDownload;

final readonly class ClipsDownloadResponse {
    /**
     * @param list<ClipDownload> $data List of clips and their download URLs.
     */
    public function __construct(
        public array $data
    ) {
    }
}
