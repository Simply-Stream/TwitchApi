<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Emote
{
    use SerializesModels;

    /**
     * @param int    $begin The index of where the Emote starts in the text.
     * @param int    $end   The index of where the Emote ends in the text.
     * @param string $id    The emote ID.
     */
    public function __construct(
        private int $begin,
        private int $end,
        private string $id
    ) {
    }

    public function getBegin(): int
    {
        return $this->begin;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
