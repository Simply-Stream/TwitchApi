<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Vip
{
    /**
     * @param string $userId    The ID of the user gaining/losing VIP status.
     * @param string $userLogin The login of the user.
     * @param string $userName  The user name of the user.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
    ) {
    }
}
