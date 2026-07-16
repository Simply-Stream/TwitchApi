<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Goals;

use DateTimeInterface;

final readonly class CreatorGoal
{
    /**
     * @param string            $id               An ID that identifies this goal.
     * @param string            $broadcasterId    An ID that identifies the broadcaster that created the goal.
     * @param string            $broadcasterName  The broadcaster’s display name.
     * @param string            $broadcasterLogin The broadcaster’s login name.
     * @param string            $type             The type of goal. Possible values are:
     *                                            - follower — The goal is to increase followers.
     *                                            - subscription — The goal is to increase subscriptions. This type
     *                                            shows the net increase or decrease in tier points associated with the
     *                                            subscriptions.
     *                                            - subscription_count — The goal is to increase subscriptions. This
     *                                            type shows the net increase or decrease in the number of
     *                                            subscriptions.
     *                                            - new_subscription — The goal is to increase subscriptions. This type
     *                                            shows only the net increase in tier points associated with the
     *                                            subscriptions (it does not account for users that unsubscribed since
     *                                            the goal started).
     *                                            - new_subscription_count — The goal is to increase subscriptions.
     *                                            This type shows only the net increase in the number of subscriptions
     *                                            (it does not account for users that unsubscribed since the goal
     *                                            started).
     * @param string            $description      A description of the goal. Is an empty string if not specified.
     * @param int               $currentAmount    The goal’s current value. The goal’s type determines how this value
     *                                            is increased or decreased.
     * @param int               $targetAmount     The goal’s target value. For example, if the broadcaster has 200
     *                                            followers before creating the goal, and their goal is to double that
     *                                            number, this field is set to 400.
     * @param DateTimeInterface $createdAt        The UTC date and time (in RFC3339 format) that the broadcaster created
     *                                            the goal.
     */
    public function __construct(
        public string $id,
        public string $broadcasterId,
        public string $broadcasterName,
        public string $broadcasterLogin,
        public string $type,
        public string $description,
        public int $currentAmount,
        public int $targetAmount,
        public DateTimeInterface $createdAt,
    ) {
    }
}
