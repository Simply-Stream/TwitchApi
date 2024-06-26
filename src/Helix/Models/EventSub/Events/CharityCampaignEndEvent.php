<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;

final readonly class CharityCampaignEndEvent extends Event
{
    /**
     * @param string            $id                  An ID that identifies the charity campaign.
     * @param string            $broadcasterId       An ID that identifies the broadcaster that ran the campaign.
     * @param string            $broadcasterLogin    The broadcaster’s login name.
     * @param string            $broadcasterName     The broadcaster’s display name.
     * @param string            $charityName         The charity’s name.
     * @param string            $charityDescription  A description of the charity.
     * @param string            $charityLogo         A URL to an image of the charity’s logo. The image’s type is PNG
     *                                               and its size is 100px X 100px.
     * @param string            $charityWebsite      A URL to the charity’s website.
     * @param CharityAmount     $currentAmount       An object that contains the final amount of donations that the
     *                                               campaign received.
     * @param CharityAmount     $targetAmount        An object that contains the campaign’s target fundraising goal.
     * @param DateTimeInterface $stoppedAt           The UTC timestamp (in RFC3339 format) of when the broadcaster
     *                                               stopped the campaign.
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
        private DateTimeInterface $stoppedAt
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

    public function getStoppedAt(): DateTimeInterface
    {
        return $this->stoppedAt;
    }
}
