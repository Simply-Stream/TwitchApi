<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

final readonly class UserActiveExtension
{
    /**
     * @param array<int, Panel>     $panel     A dictionary that contains the data for a panel extension. The
     *                                         dictionary's key is a sequential number beginning with 1. Note that JSON
     *                                         string keys become PHP integers.
     * @param array<int, Overlay>   $overlay   A dictionary that contains the data for a video-overlay extension.
     * @param array<int, Component> $component A dictionary that contains the data for a video-component extension.
     */
    public function __construct(
        public array $panel,
        public array $overlay,
        public array $component,
    ) {
    }
}
