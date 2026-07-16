<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

use DateTimeInterface;

final readonly class CustomReward
{
    /**
     * @param string                     $broadcasterId                     The ID that uniquely identifies the
     *                                                                      broadcaster.
     * @param string                     $broadcasterLogin                  The broadcaster’s login name.
     * @param string                     $broadcasterName                   The broadcaster’s display name.
     * @param string                     $id                                The ID that uniquely identifies this custom
     *                                                                      reward.
     * @param string                     $title                             The title of the reward.
     * @param string                     $prompt                            The prompt shown to the viewer when they
     *                                                                      redeem the reward if user input is required.
     * @param int                        $cost                              The cost of the reward in Channel Points.
     * @param array<string, mixed>       $defaultImage                      A set of default images for the reward.
     * @param string                     $backgroundColor                   The background color to use for the reward.
     *                                                                      The color is in Hex format (for example,
     *                                                                      #00E5CB).
     * @param bool                       $isEnabled                         A Boolean value that determines whether the
     *                                                                      reward is enabled. Disabled rewards aren’t
     *                                                                      shown to the user.
     * @param bool                       $isUserInputRequired               A Boolean value that determines whether the
     *                                                                      user must enter information when redeeming
     *                                                                      the reward.
     * @param MaxPerStreamSetting        $maxPerStreamSetting               The settings used to determine whether to
     *                                                                      apply a maximum to the number of redemptions
     *                                                                      allowed per live stream.
     * @param MaxPerUserPerStreamSetting $maxPerUserPerStreamSetting        The settings used to determine whether to
     *                                                                      apply a maximum to the number of redemptions
     *                                                                      allowed per user per live stream.
     * @param GlobalCooldownSetting      $globalCooldownSetting             The settings used to determine whether to
     *                                                                      apply a cooldown period between redemptions
     *                                                                      and the length of the cooldown.
     * @param bool                       $isPaused                          A Boolean value that determines whether the
     *                                                                      reward is currently paused. Viewers can’t
     *                                                                      redeem paused rewards.
     * @param bool                       $isInStock                         A Boolean value that determines whether the
     *                                                                      reward is currently in stock. Viewers can’t
     *                                                                      redeem out of stock rewards.
     * @param bool                       $shouldRedemptionsSkipRequestQueue A Boolean value that determines whether
     *                                                                      redemptions should be set to FULFILLED
     *                                                                      status immediately when redeemed. If false,
     *                                                                      status is UNFULFILLED and follows the normal
     *                                                                      request queue process.
     * @param int|null                   $redemptionsRedeemedCurrentStream  The number of redemptions redeemed during
     *                                                                      the current live stream. Counts against the
     *                                                                      max_per_stream_setting limit. Null if the
     *                                                                      stream isn’t live or max_per_stream_setting
     *                                                                      isn’t enabled.
     * @param array<string, mixed>|null  $image                             A set of custom images for the reward. Null
     *                                                                      if the broadcaster didn’t upload images.
     * @param DateTimeInterface|null     $cooldownExpiresAt                 The timestamp of when the cooldown period
     *                                                                      expires. Null if the reward isn’t in a
     *                                                                      cooldown state.
     */
    public function __construct(
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
        public string $id,
        public string $title,
        public string $prompt,
        public int $cost,
        public array $defaultImage,
        public string $backgroundColor,
        public bool $isEnabled,
        public bool $isUserInputRequired,
        public MaxPerStreamSetting $maxPerStreamSetting,
        public MaxPerUserPerStreamSetting $maxPerUserPerStreamSetting,
        public GlobalCooldownSetting $globalCooldownSetting,
        public bool $isPaused,
        public bool $isInStock,
        public bool $shouldRedemptionsSkipRequestQueue,
        public ?int $redemptionsRedeemedCurrentStream = null,
        public ?array $image = null,
        public ?DateTimeInterface $cooldownExpiresAt = null,
    ) {
    }
}
