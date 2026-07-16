<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Warn
{
    /**
     * @param string        $userId        The ID of the user being warned.
     * @param string        $userLogin     The login of the user being warned.
     * @param string        $userName      The user name of the user being warned.
     * @param string|null   $reason        Optional. Reason given for the warning.
     * @param string[]|null $chatRulesCited Optional. Chat rules cited for the warning.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public ?string $reason = null,
        public ?array $chatRulesCited = null,
    ) {
    }
}
