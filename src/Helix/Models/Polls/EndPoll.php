<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Polls;

use Webmozart\Assert\Assert;

final readonly class EndPoll
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that’s running the poll. This ID must match the user ID
     *                              in the user access token.
     * @param string $id            The ID of the poll to update.
     * @param string $status        The status to set the poll to. Possible case-sensitive values are:
     *                              - TERMINATED — Ends the poll before the poll is scheduled to end. The poll remains
     *                              publicly visible.
     *                              - ARCHIVED — Ends the poll before the poll is scheduled to end, and then archives
     *                              it so it's no longer publicly visible.
     */
    public function __construct(
        public string $broadcasterId,
        public string $id,
        public string $status
    ) {
        Assert::stringNotEmpty($this->broadcasterId, 'Broadcaster ID can\'t be empty');
        Assert::stringNotEmpty($this->id, 'ID can\'t be empty');
        Assert::stringNotEmpty($this->status, 'Status can\'t be empty');

        Assert::inArray(
            $this->status,
            ['TERMINATED', 'ARCHIVED'],
            'Status can only be one of the following values: %2$s. Got %s'
        );
    }
}
