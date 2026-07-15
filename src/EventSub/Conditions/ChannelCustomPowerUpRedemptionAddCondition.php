<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ChannelCustomPowerUpRedemptionAddCondition implements ConditionInterface
{
    /**
     * @param string      $broadcasterUserId The broadcaster user ID for the channel you want to receive custom
     *                                       Power-up redemption add notifications for.
     * @param string|null $rewardId          Optional. Specify a reward id to only receive notifications for a
     *                                       specific custom Power-up.
     */
    public function __construct(
        public string $broadcasterUserId,
        public ?string $rewardId = null,
    ) {
    }
}
