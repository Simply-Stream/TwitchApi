<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

use DateTimeInterface;

final readonly class Timeout
{
    /**
     * @param string            $userId    The ID of the user being timed out.
     * @param string            $userLogin The login of the user being timed out.
     * @param string            $userName  The user name of the user being timed out.
     * @param DateTimeInterface $expiresAt The time at which the timeout ends.
     * @param string|null       $reason    Optional. The reason given for the timeout.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public DateTimeInterface $expiresAt,
        public ?string $reason = null,
    ) {
    }
}
