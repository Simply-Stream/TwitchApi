<?php
declare(strict_types=1);
namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Followers
{
    /** @param int $followDurationMinutes The length of time, in minutes, that followers must have followed to chat. */
    public function __construct(public int $followDurationMinutes) {}
}
