<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\DropEntitlementGrantCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'drop.entitlement.grant', version: '1', condition: DropEntitlementGrantCondition::class)]
final readonly class DropEntitlementGrantEvent implements EventInterface
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
