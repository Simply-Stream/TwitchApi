<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotification;

final readonly class PrimePaidUpgrade
{
    /**
     * @param string $subTier The type of subscription plan being used. Possible values are:
     *                        - 1000 — First level of paid subscription
     *                        - 2000 — Second level of paid subscription
     *                        - 3000 — Third level of paid subscription
     */
    public function __construct(
        public string $subTier
    ) {
    }
}
