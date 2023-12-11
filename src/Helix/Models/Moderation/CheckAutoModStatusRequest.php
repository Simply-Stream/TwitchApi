<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class CheckAutoModStatusRequest extends AbstractModel
{
    /**
     * @param CheckAutoModStatus[] $data The list of messages to check. The list must contain at least one message
     *                                   and may contain up to a maximum of 100 messages.
     */
    public function __construct(
        private array $data
    ) {
        Assert::lessThanEq(count($this->data), 100);
    }

    public function getData(): array
    {
        return $this->data;
    }
}
