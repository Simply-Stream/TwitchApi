<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

/**
 * Contains the information used to page through the list of results. The object is empty if there are no more pages
 * left to page through.
 *
 * @see https://dev.twitch.tv/docs/api/guide#pagination
 */
class Pagination
{
    use SerializesModels;

    /**
     * @param string|null $cursor The cursor used to get the next page of results. Use the cursor to set the request’s
     *                            after query parameter.
     */
    public function __construct(
        protected ?string $cursor = null
    ) {
    }

    public function getCursor(): ?string
    {
        return $this->cursor;
    }
}
