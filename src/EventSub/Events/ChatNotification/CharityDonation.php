<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChatNotification;

use SimplyStream\TwitchApi\EventSub\Shared\Amount;

final readonly class CharityDonation
{
    /**
     * @param string $charityName Name of the charity.
     * @param Amount $amount      An object that contains the amount of money that the user paid.
     */
    public function __construct(
        public string $charityName,
        public Amount $amount
    ) {
    }
}
