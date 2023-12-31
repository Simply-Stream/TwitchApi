<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Charity;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class CharityCampaign
{
    use SerializesModels;

    /**
     * @param string        $id                 An ID that identifies the charity campaign.
     * @param string        $broadcasterId      An ID that identifies the broadcaster that’s running the campaign.
     * @param string        $broadcasterLogin   The broadcaster’s login name.
     * @param string        $broadcasterName    The broadcaster’s display name.
     * @param string        $charityName        The charity’s name.
     * @param string        $charityDescription A description of the charity.
     * @param string        $charityLogo        A URL to an image of the charity’s logo. The image’s type is PNG and
     *                                          its size is 100px X
     *                                          100px.
     * @param string        $charityWebsite     A URL to the charity’s website.
     * @param CharityAmount $currentAmount      The current amount of donations that the campaign has received.
     * @param CharityAmount $targetAmount       The campaign’s fundraising goal. This field is null if the broadcaster
     *                                          has not defined a fundraising goal.
     */
    public function __construct(
        private string $id,
        private string $broadcasterId,
        private string $broadcasterLogin,
        private string $broadcasterName,
        private string $charityName,
        private string $charityDescription,
        private string $charityLogo,
        private string $charityWebsite,
        private CharityAmount $currentAmount,
        private CharityAmount $targetAmount,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getBroadcasterLogin(): string
    {
        return $this->broadcasterLogin;
    }

    public function getBroadcasterName(): string
    {
        return $this->broadcasterName;
    }

    public function getCharityName(): string
    {
        return $this->charityName;
    }

    public function getCharityDescription(): string
    {
        return $this->charityDescription;
    }

    public function getCharityLogo(): string
    {
        return $this->charityLogo;
    }

    public function getCharityWebsite(): string
    {
        return $this->charityWebsite;
    }

    public function getCurrentAmount(): CharityAmount
    {
        return $this->currentAmount;
    }

    public function getTargetAmount(): CharityAmount
    {
        return $this->targetAmount;
    }
}
