<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

use DateTimeInterface;

final readonly class CustomRewardRedemption
{
    /**
     * @param string            $broadcasterId    The ID that uniquely identifies the broadcaster.
     * @param string            $broadcasterLogin The broadcaster’s login name.
     * @param string            $broadcasterName  The broadcaster’s display name.
     * @param string            $id               The ID that uniquely identifies this redemption.
     * @param string            $userId           The ID that uniquely identifies the user that redeemed the reward.
     * @param string            $userLogin        The user’s login name.
     * @param string            $userName         The user’s display name.
     * @param string            $status           The state of the redemption. Possible values are:
     *                                            - CANCELED
     *                                            - FULFILLED
     *                                            - UNFULFILLED
     * @param DateTimeInterface $redeemedAt       The date and time of when the reward was redeemed, in RFC3339 format.
     * @param Reward            $reward           The reward that the user redeemed.
     * @param string|null       $userInput        The text the user entered at the prompt when they redeemed the reward;
     *                                            otherwise, an empty string if user input was not required.
     */
    public function __construct(
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
        public string $id,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $status,
        public DateTimeInterface $redeemedAt,
        public Reward $reward,
        public ?string $userInput = null,
    ) {
    }
}
