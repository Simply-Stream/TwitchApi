<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Polls;

use DateTimeInterface;

final readonly class Poll
{
    /**
     * @param string                 $id                         An ID that identifies the poll.
     * @param string                 $broadcasterId              An ID that identifies the broadcaster that created the
     *                                                           poll.
     * @param string                 $broadcasterName            The broadcaster’s display name.
     * @param string                 $broadcasterLogin           The broadcaster’s login name.
     * @param string                 $title                      The question that viewers are voting on. For example,
     *                                                           What game should I play next? The title may contain a
     *                                                           maximum of 60 characters.
     * @param list<Choice>           $choices                    A list of choices that viewers can choose from. The
     *                                                           list will contain a minimum of two choices and up to a
     *                                                           maximum of five choices.
     * @param bool                   $bitsVotingEnabled          Not used; will be set to false.
     * @param int                    $bitsPerVote                Not used; will be set to 0.
     * @param bool                   $channelPointsVotingEnabled A Boolean value that indicates whether viewers may cast
     *                                                           additional votes using Channel Points.
     * @param int                    $channelPointsPerVote       The number of points the viewer must spend to cast one
     *                                                           additional vote.
     * @param string                 $status                     The poll’s status. Valid values are:
     *                                                           - ACTIVE
     *                                                           - COMPLETED
     *                                                           - TERMINATED
     *                                                           - ARCHIVED
     *                                                           - MODERATED
     *                                                           - INVALID
     * @param int                    $duration                   The length of time (in seconds) that the poll will run
     *                                                           for.
     * @param DateTimeInterface      $startedAt                  The UTC date and time (in RFC3339 format) of when the
     *                                                           poll began.
     * @param DateTimeInterface|null $endedAt                    The UTC date and time (in RFC3339 format) of when the
     *                                                           poll ended. If status is ACTIVE, this field is set to
     *                                                           null.
     */
    public function __construct(
        public string $id,
        public string $broadcasterId,
        public string $broadcasterName,
        public string $broadcasterLogin,
        public string $title,
        public array $choices,
        public bool $bitsVotingEnabled,
        public int $bitsPerVote,
        public bool $channelPointsVotingEnabled,
        public int $channelPointsPerVote,
        public string $status,
        public int $duration,
        public DateTimeInterface $startedAt,
        public ?DateTimeInterface $endedAt = null,
    ) {
    }
}
