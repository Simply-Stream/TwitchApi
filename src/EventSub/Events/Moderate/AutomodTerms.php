<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class AutomodTerms
{
    /**
     * @param string   $action      Either "add" or "remove".
     * @param string   $list        Either "blocked" or "permitted".
     * @param string[] $terms       Terms being added or removed.
     * @param bool     $fromAutomod Whether the terms were added due to an Automod message approve/deny action.
     */
    public function __construct(
        public string $action,
        public string $list,
        public array $terms,
        public bool $fromAutomod,
    ) {
    }
}
