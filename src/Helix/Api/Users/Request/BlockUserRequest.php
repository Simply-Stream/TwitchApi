<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

use SimplyStream\TwitchApi\Helix\Api\Users\BlockReason;
use SimplyStream\TwitchApi\Helix\Api\Users\SourceContext;

final readonly class BlockUserRequest
{
    /**
     * @param string             $targetUserId  The ID of the user to block. The API ignores the request if the
     *                                          broadcaster has already blocked the user.
     * @param SourceContext|null $sourceContext The location where the harassment took place that is causing the
     *                                          broadcaster to block the user.
     * @param BlockReason|null   $reason        The reason that the broadcaster is blocking the user.
     */
    public function __construct(
        public string $targetUserId,
        public ?SourceContext $sourceContext = null,
        public ?BlockReason $reason = null,
    ) {}
}
