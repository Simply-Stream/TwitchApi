<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use Webmozart\Assert\Assert;

final readonly class CheckAutoModStatus
{
    /**
     * @param list<AutoModMessage> $data The messages to check. You may check a maximum of 100 messages per request.
     */
    public function __construct(
        public array $data,
    ) {
        Assert::minCount($data, 1);
        Assert::maxCount($data, 100);
        Assert::allIsInstanceOf($data, AutoModMessage::class);
    }
}
