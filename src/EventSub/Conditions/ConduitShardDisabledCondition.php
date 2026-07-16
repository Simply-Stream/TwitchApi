<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Conditions;

use SimplyStream\TwitchApi\EventSub\ConditionInterface;

final readonly class ConduitShardDisabledCondition implements ConditionInterface
{
    /**
     * @param string      $clientId  Your application’s client id. Must match the client ID in the application access
     *                              token.
     * @param string|null $conduitId Optional. The conduit ID to receive events for. If omitted, events for all of
     *                              this client’s conduits are sent.
     */
    public function __construct(
        public string $clientId,
        public ?string $conduitId = null,
    ) {
    }
}
