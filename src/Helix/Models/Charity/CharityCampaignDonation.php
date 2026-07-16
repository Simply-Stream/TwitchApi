<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Charity;

final readonly class CharityCampaignDonation
{
    /**
     * @param string        $id         An ID that identifies the donation. The ID is unique across campaigns.
     * @param string        $campaignId An ID that identifies the charity campaign that the donation applies to.
     * @param string        $userId     An ID that identifies a user that donated money to the campaign.
     * @param string        $userLogin  The user’s login name.
     * @param string        $userName   The user’s display name.
     * @param CharityAmount $amount     An object that contains the amount of money that the user donated.
     */
    public function __construct(
        public string $id,
        public string $campaignId,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public CharityAmount $amount,
    ) {
    }
}
