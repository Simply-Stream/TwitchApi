<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\CharityCampaignStopCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;

#[EventSubSubscription(type: 'channel.charity_campaign.stop', version: '1', condition: CharityCampaignStopCondition::class)]
final readonly class CharityCampaignStopEvent implements EventInterface
{
    /**
     * @param string            $id                 An ID that identifies the charity campaign.
     * @param string            $broadcasterId      An ID that identifies the broadcaster that’s running the campaign.
     * @param string            $broadcasterLogin   The broadcaster’s login name.
     * @param string            $broadcasterName    The broadcaster’s display name.
     * @param string            $charityName        The charity’s name.
     * @param string            $charityDescription A description of the charity.
     * @param string            $charityLogo        A URL to an image of the charity’s logo. The image’s type is PNG
     *                                              and its size is 100px X 100px.
     * @param string            $charityWebsite     A URL to the charity’s website.
     * @param CharityAmount     $currentAmount      An object that contains the current amount of donations that the
     *                                              campaign has received.
     * @param CharityAmount     $targetAmount       An object that contains the campaign’s target fundraising goal.
     * @param DateTimeInterface $stoppedAt          The UTC timestamp (in RFC3339 format) of when the broadcaster stopped the campaign.
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
        public CharityAmount $targetAmount,
        public DateTimeInterface $stoppedAt
    ) {
    }
}
