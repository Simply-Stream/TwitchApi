<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

final readonly class GetExtensionLiveChannelsRequest
{
    /**
     * @param string      $extensionId The ID of the extension to get.
     * @param int         $first       The maximum number of items to return per page. Minimum 1, maximum 100.
     * @param string|null $after       The cursor used to get the next page of results.
     */
    public function __construct(
        public string $extensionId,
        public int $first = 20,
        public ?string $after = null,
    ) {
    }
}
