<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

use SimplyStream\TwitchApi\EventSub\Subscription;

interface EventSubMessageInterface
{
    public function metadata(): EventSubMetadata;

    public function subscription(): Subscription;
}
