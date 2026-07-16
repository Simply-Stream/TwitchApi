<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Charity;

final readonly class CharityCampaign
{
    /**
     * @param string             $id                 An ID that identifies the charity campaign.
     * @param string             $broadcasterId      An ID that identifies the broadcaster that’s running the campaign.
     * @param string             $broadcasterLogin   The broadcaster’s login name.
     * @param string             $broadcasterName    The broadcaster’s display name.
     * @param string             $charityName        The charity’s name.
     * @param string             $charityDescription A description of the charity.
     * @param string             $charityLogo        A URL to an image of the charity’s logo. The image’s type is PNG
     *                                               and its size is 100px x 100px.
     * @param string             $charityWebsite     A URL to the charity’s website.
     * @param CharityAmount      $currentAmount      The current amount of donations that the campaign has received.
     * @param CharityAmount|null $targetAmount       The campaign’s fundraising goal. This field is null if the
     *                                               broadcaster has not defined a fundraising goal.
     */
    public function __construct(
        public string $id,
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
        public string $charityName,
        public string $charityDescription,
        public string $charityLogo,
        public string $charityWebsite,
        public CharityAmount $currentAmount,
        public ?CharityAmount $targetAmount = null,
    ) {
    }
}
