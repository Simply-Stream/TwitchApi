<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Bits\Request;

final readonly class GetCheermotesRequest
{
    /**
     * @param string|null $broadcasterId The ID of the broadcaster whose custom Cheermotes you want to get. Specify the
     *                                   broadcaster’s ID if you want to include the broadcaster’s Cheermotes in the
     *                                   response (not all broadcasters upload Cheermotes). If not specified, the
     *                                   response contains only global Cheermotes.
     *
     *                                   If the broadcaster uploaded Cheermotes, the type field in the response is set
     *                                   to channel_custom.
     */
    public function __construct(
        public ?string $broadcasterId = null,
    ) {
    }
}
