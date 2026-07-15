<?php
declare(strict_types=1);
namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Unraid
{
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
    ) {}
}
