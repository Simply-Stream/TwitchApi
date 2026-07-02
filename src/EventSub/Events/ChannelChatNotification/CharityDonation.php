<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotification;

use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;

final readonly class CharityDonation
{
    /**
     * @param string        $charityName Name of the charity.
     * @param CharityAmount $amount      An object that contains the amount of money that the user paid.
     */
    public function __construct(
        public string $charityName,
        public CharityAmount $amount
    ) {
    }
}
