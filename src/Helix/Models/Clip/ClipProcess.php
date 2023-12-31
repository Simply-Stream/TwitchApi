<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Clip;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ClipProcess
{
    use SerializesModels;

    /**
     * @param string $editUrl A URL that you can use to edit the clip’s title, identify the part of the clip to
     *                        publish, and publish the clip.
     *
     *                        The URL is valid for up to 24 hours or until the clip is published, whichever comes
     *                        first.
     * @param string $id      An ID that uniquely identifies the clip.
     */
    public function __construct(
        private string $editUrl,
        private string $id
    ) {
    }

    public function getEditUrl(): string
    {
        return $this->editUrl;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
