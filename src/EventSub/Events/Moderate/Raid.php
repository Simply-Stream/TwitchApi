<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Raid
{
    /**
     * @param string $userId      The ID of the user being raided.
     * @param string $userLogin   The login of the user being raided.
     * @param string $userName    The user name of the user being raided.
     * @param int    $viewerCount The viewer count.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public int $viewerCount,
    ) {
    }
}
