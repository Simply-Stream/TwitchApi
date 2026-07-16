<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class UnbanRequest
{
    /**
     * @param bool   $isApproved      Whether or not the unban request was approved or denied.
     * @param string $userId          The ID of the banned user.
     * @param string $userLogin       The login of the user.
     * @param string $userName        The user name of the user.
     * @param string $moderatorMessage The message included by the moderator explaining their approval or denial.
     */
    public function __construct(
        public bool $isApproved,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $moderatorMessage,
    ) {
    }
}
