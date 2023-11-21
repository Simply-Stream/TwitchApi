<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Announcement
{
    use SerializesModels;

    /**
     * @param string $color Color of the announcement.
     */
    public function __construct(
        private string $color
    ) {
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
