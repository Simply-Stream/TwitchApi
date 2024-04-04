<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;

final readonly class CharityCampaignStartEvent extends Event
{
    /**
     * @param string            $id                   An ID that identifies the charity campaign.
     * @param string            $broadcasterUserId    An ID that identifies the broadcaster that’s running the campaign.
     * @param string            $broadcasterUserLogin The broadcaster’s login name.
     * @param string            $broadcasterUserName  The broadcaster’s display name.
     * @param string            $charityName          The charity’s name.
     * @param string            $charityDescription   A description of the charity.
     * @param string            $charityLogo          A URL to an image of the charity’s logo. The image’s type is PNG
     *                                                and its size is 100px X 100px.
     * @param string            $charityWebsite       A URL to the charity’s website.
     * @param CharityAmount     $currentAmount        An object that contains the current amount of donations that the
     *                                                campaign has received.
     * @param CharityAmount     $targetAmount         An object that contains the campaign’s target fundraising goal.
     * @param DateTimeInterface $startedAt            The UTC timestamp (in RFC3339 format) of when the broadcaster
     *                                                started the campaign.
     */
    public function __construct(
        private string $id,
        private string $broadcasterUserId,
        private string $broadcasterUserLogin,
        private string $broadcasterUserName,
        private string $charityName,
        private string $charityDescription,
        private string $charityLogo,
        private string $charityWebsite,
        private CharityAmount $currentAmount,
        private CharityAmount $targetAmount,
        private DateTimeInterface $startedAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBroadcasterUserId(): string
    {
        return $this->broadcasterUserId;
    }

    public function getBroadcasterUserLogin(): string
    {
        return $this->broadcasterUserLogin;
    }

    public function getBroadcasterUserName(): string
    {
        return $this->broadcasterUserName;
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

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }
}
