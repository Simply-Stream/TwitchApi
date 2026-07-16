<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Ban
{
    /**
     * @param string      $userId    The ID of the user being banned.
     * @param string      $userLogin The login of the user being banned.
     * @param string      $userName  The user name of the user being banned.
     * @param string|null $reason    Optional. Reason given for the ban.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public ?string $reason = null,
    ) {
    }
}
