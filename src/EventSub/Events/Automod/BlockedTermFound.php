<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Automod;

final readonly class BlockedTermFound
{
    /**
     * @param string          $termId                         The id of the blocked term found.
     * @param MessageBoundary $boundary                       The bounds of the text that caused the message to be
     *                                                        caught.
     * @param string          $ownerBroadcasterUserId         The id of the broadcaster that owns the blocked term.
     * @param string          $ownerBroadcasterUserLogin      The login of the broadcaster that owns the blocked
     *                                                        term.
     * @param string          $ownerBroadcasterUserName       The username of the broadcaster that owns the blocked
     *                                                        term.
     */
    public function __construct(
        public string $termId,
        public MessageBoundary $boundary,
        public string $ownerBroadcasterUserId,
        public string $ownerBroadcasterUserLogin,
        public string $ownerBroadcasterUserName,
    ) {
    }
}
