<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Clip;

final readonly class ClipProcess
{
    /**
     * @param string $editUrl A URL that you can use to edit the clip’s title, identify the part of the clip to
     *                        publish, and publish the clip.
     *
     *                        The URL is valid for up to 24 hours or until the clip is published, whichever comes
     *                        first.
     * @param string $id      An ID that uniquely identifies the clip.
     */
    public function __construct(
        public string $editUrl,
        public string $id,
    ) {
    }
}
