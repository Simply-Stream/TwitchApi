<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelPointsCustomRewardAddCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointCustomReward\GlobalCooldown;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointCustomReward\MaxPerStream;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointCustomReward\MaxPerUserPerStream;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;

#[EventSubSubscription(type: 'channel.channel_points_custom_reward.add', version: '1', condition: ChannelPointsCustomRewardAddCondition::class)]
final readonly class ChannelPointsCustomRewardAddEvent implements EventInterface
{
    /**
     * @param string                 $id                                 The reward identifier.
     * @param string                 $broadcasterUserId                  The requested broadcaster ID.
     * @param string                 $broadcasterUserLogin               The requested broadcaster login.
     * @param string                 $broadcasterUserName                The requested broadcaster display name.
     * @param bool                   $isEnabled                          Is the reward currently enabled. If false, the
     *                                                                   reward won’t show up to viewers.
     * @param bool                   $isPaused                           Is the reward currently paused. If true,
     *                                                                   viewers can’t redeem.
     * @param bool                   $isInStock                          Is the reward currently in stock. If false,
     *                                                                   viewers can’t redeem.
     * @param string                 $title                              The reward title.
     * @param int                    $cost                               The reward cost.
     * @param string                 $prompt                             The reward description.
     * @param bool                   $isUserInputRequired                Does the viewer need to enter information when
     *                                                                   redeeming the reward.
     * @param bool                   $shouldRedemptionsSkipRequestQueue  Should redemptions be set to fulfilled status
     *                                                                   immediately when redeemed and skip the request
     *                                                                   queue instead of the normal unfulfilled
     *                                                                   status.
     * @param MaxPerStream           $maxPerStream                       Whether a maximum per stream is enabled and
     *                                                                   what the maximum is.
     * @param MaxPerUserPerStream    $maxPerUserPerStream                Whether a maximum per user per stream is
     *                                                                   enabled and what the maximum is.
     * @param string                 $backgroundColor                    Custom background color for the reward.
     *                                                                   Format: Hex with # prefix. Example: #FA1ED2.
     * @param Image                  $image                              Set of custom images of 1x, 2x and 4x sizes
     *                                                                   for the reward. Can be null if no images have
     *                                                                   been uploaded.
     * @param Image                  $defaultImage                       Set of default images of 1x, 2x and 4x sizes
     *                                                                   for the reward.
     * @param GlobalCooldown         $globalCooldown                     Whether a cooldown is enabled and what the
     *                                                                   cooldown is in seconds.
     * @param DateTimeInterface|null $cooldownExpiresAt                  Timestamp of the cooldown expiration. null if
     *                                                                   the reward isn’t on cooldown.
     * @param int|null               $redemptionsRedeemedCurrentStream   The number of redemptions redeemed during the
     *                                                                   current live stream. Counts against the
     *                                                                   max_per_stream limit. null if the broadcasters
     *                                                                   stream isn’t live or max_per_stream isn’t
     *                                                                   enabled.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public bool $isEnabled,
        public bool $isPaused,
        public bool $isInStock,
        public string $title,
        public int $cost,
        public string $prompt,
        public bool $isUserInputRequired,
        public bool $shouldRedemptionsSkipRequestQueue,
        public MaxPerStream $maxPerStream,
        public MaxPerUserPerStream $maxPerUserPerStream,
        public string $backgroundColor,
        public Image $image,
        public Image $defaultImage,
        public GlobalCooldown $globalCooldown,
        public ?DateTimeInterface $cooldownExpiresAt = null,
        public ?int $redemptionsRedeemedCurrentStream = null
    ) {
    }
}
