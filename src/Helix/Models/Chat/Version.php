<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class Version
{
    /**
     * @param string      $id          An ID that identifies this version of the badge.
     * @param string      $imageUrl1x  A URL to the small version (18px x 18px) of the badge.
     * @param string      $imageUrl2x  A URL to the medium version (36px x 36px) of the badge.
     * @param string      $imageUrl4x  A URL to the large version (72px x 72px) of the badge.
     * @param string      $title       The title of the badge.
     * @param string      $description The description of the badge.
     * @param string|null $clickAction The action to take when clicking on the badge. Null if no action is specified.
     * @param string|null $clickUrl    The URL to navigate to when clicking on the badge. Null if no URL is specified.
     */
    public function __construct(
        public string $id,
        public string $imageUrl1x,
        public string $imageUrl2x,
        public string $imageUrl4x,
        public string $title,
        public string $description,
        public ?string $clickAction = null,
        public ?string $clickUrl = null,
    ) {
    }
}
