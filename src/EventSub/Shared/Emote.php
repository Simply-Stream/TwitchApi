<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class Emote
{
    /**
     * @param string      $id         An ID that uniquely identifies this emote.
     * @param string      $emoteSetId An ID that identifies the emote set that the emote belongs to.
     * @param string|null $ownerId    The ID of the broadcaster who owns the emote.
     * @param string[]    $format     The formats that the emote is available in. For example, if the emote is available
     *                                only as a static PNG, the array contains only static. But if the emote is available
     *                                as a static PNG and an animated GIF, the array contains static and animated. The
     *                                possible formats are:
     *                                - animated — An animated GIF is available for this emote.
     *                                - static — A static PNG file is available for this emote.
     */
    public function __construct(
        public string $id,
        public string $emoteSetId,
        public ?string $ownerId,
        public ?array $format
    ) {
    }
}
