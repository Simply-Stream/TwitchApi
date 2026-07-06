<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use SimplyStream\TwitchApi\Helix\Models\Moderation\AddBlockedTerm;

final readonly class AddBlockedTermRequest
{
    /**
     * @param string         $broadcasterId The ID of the broadcaster that owns the list of blocked terms.
     * @param string         $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                                      broadcaster’s chat room. This ID must match the user ID in the user access
     *                                      token.
     * @param AddBlockedTerm $term          The term to block.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public AddBlockedTerm $term,
    ) {}
}
