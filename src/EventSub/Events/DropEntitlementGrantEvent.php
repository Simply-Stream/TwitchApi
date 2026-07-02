<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

final readonly class DropEntitlementGrantEvent
{
    /**
     * @param string                   $id   Individual event ID, as assigned by EventSub. Use this for de-duplicating
     *                                       messages.
     * @param DropEntitlementGrantData $data Entitlement object.
     */
    public function __construct(
        public string $id,
        public DropEntitlementGrantData $data
    ) {
    }
}
