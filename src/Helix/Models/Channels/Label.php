<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Channels;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Label
{
    use SerializesModels;

    /**
     * @param string $id        ID of the Content Classification Labels that must be added/removed from the channel.
     *                          Can be one of the following values:
     *                          - DrugsIntoxication
     *                          - SexualThemes
     *                          - ViolentGraphic
     *                          - Gambling
     *                          - ProfanityVulgarity
     * @param bool   $isEnabled Boolean flag indicating whether the label should be enabled (true) or disabled for the
     *                          channel.
     */
    public function __construct(
        private string $id,
        private bool $isEnabled
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
