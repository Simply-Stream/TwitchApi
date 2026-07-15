<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class UserWhisperMessageCondition implements ConditionInterface
{
    /**
     * @param string $userId The user_id of the person receiving whispers.
     */
    public function __construct(
        public string $userId,
    ) {
    }
}
