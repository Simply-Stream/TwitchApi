<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events\Notifications;

use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class CharityDonation
{
    use SerializesModels;

    /**
     * @param string        $charityName Name of the charity.
     * @param CharityAmount $amount      An object that contains the amount of money that the user paid.
     */
    public function __construct(
        private string $charityName,
        private CharityAmount $amount
    ) {
    }

    public function getCharityName(): string
    {
        return $this->charityName;
    }

    public function getAmount(): CharityAmount
    {
        return $this->amount;
    }
}
