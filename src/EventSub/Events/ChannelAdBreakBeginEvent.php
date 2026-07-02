<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelAdBreakBeginCondition;

#[EventSubSubscription(type: 'channel.ad_break.begin', version: '1', condition: ChannelAdBreakBeginCondition::class)]
final readonly class ChannelAdBreakBeginEvent
{
    /**
     * @param int               $durationSeconds       Length in seconds of the mid-roll ad break requested
     * @param DateTimeInterface $timestamp             The UTC timestamp of when the ad break began, in RFC3339 format.
     *                                                 Note that there is potential delay between this event, when the
     *                                                 streamer requested the ad break, and when the viewers will see
     *                                                 ads.
     * @param bool              $isAutomatic           Indicates if the ad was automatically scheduled via Ads Manager
     * @param string            $requesterUserId       The ID of the user that requested the ad. For automatic ads,
     *                                                 this will be the ID of the broadcaster.
     * @param string            $broadcasterUserId     The broadcaster’s user ID for the channel the ad was run on.
     * @param string            $broadcasterUserLogin  The broadcaster’s user login for the channel the ad was run on.
     * @param string            $broadcasterUserName   The broadcaster’s user display name for the channel the ad was
     *                                                 run on.
     */
    public function __construct(
        public int $durationSeconds,
        public DateTimeInterface $timestamp,
        public bool $isAutomatic,
        public string $requesterUserId,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
    ) {
    }
}
