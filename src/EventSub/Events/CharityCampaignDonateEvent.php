<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\CharityCampaignDonateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;

#[EventSubSubscription(type: 'channel.charity_campaign.donate', version: '1', condition: CharityCampaignDonateCondition::class)]
final readonly class CharityCampaignDonateEvent implements EventInterface
{
    /**
     * @param string        $id                   An ID that identifies the donation. The ID is unique across campaigns.
     * @param string        $campaignId           An ID that identifies the charity campaign.
     * @param string        $broadcasterUserId    An ID that identifies the broadcaster that’s running the campaign.
     * @param string        $broadcasterUserLogin The broadcaster’s login name.
     * @param string        $broadcasterUserName  The broadcaster’s display name.
     * @param string        $userId               An ID that identifies the user that donated to the campaign.
     * @param string        $userLogin            The user’s login name.
     * @param string        $userName             The user’s display name.
     * @param string        $charityName          The charity’s name.
     * @param string        $charityDescription   A description of the charity.
     * @param string        $charityLogo          A URL to an image of the charity’s logo. The image’s type is PNG and
     *                                            its size is 100px X
     *                                            100px.
     * @param string        $charityWebsite       A URL to the charity’s website.
     * @param CharityAmount $amount               An object that contains the amount of money that the user donated.
     */
    public function __construct(
        public string $id,
        public string $campaignId,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $charityName,
        public string $charityDescription,
        public string $charityLogo,
        public string $charityWebsite,
        public CharityAmount $amount
    ) {
    }
}
