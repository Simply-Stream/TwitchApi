<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

interface EventSubMessageInterface
{
    public function metadata(): EventSubMetadata;
}
