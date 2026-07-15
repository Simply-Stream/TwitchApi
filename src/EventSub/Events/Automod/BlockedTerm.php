<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Automod;

final readonly class BlockedTerm
{
    /**
     * @param BlockedTermFound[] $termsFound The list of blocked terms found in the message.
     */
    public function __construct(
        public array $termsFound,
    ) {
    }
}
