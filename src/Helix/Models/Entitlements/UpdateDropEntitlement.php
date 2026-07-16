<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Entitlements;

use Webmozart\Assert\Assert;

final readonly class UpdateDropEntitlement
{
    /**
     * @param list<string> $entitlementIds    A list of IDs that identify the entitlements to update. You may specify a
     *                                        maximum of 100 IDs.
     * @param string|null  $fulfillmentStatus The fulfillment status to set the entitlements to. Possible values are:
     *                                        - CLAIMED — The user claimed the benefit.
     *                                        - FULFILLED — The developer granted the benefit that the user claimed.
     */
    public function __construct(
        public array $entitlementIds = [],
        public ?string $fulfillmentStatus = null,
    ) {
        if (null !== $this->fulfillmentStatus) {
            Assert::inArray(
                $this->fulfillmentStatus,
                ['CLAIMED', 'FULFILLED'],
                'Fulfilment status got an invalid value. Allowed values are: CLAIMED, FULFILLED. Got %s',
            );
        }

        Assert::allString($this->entitlementIds, 'Only strings are allowed as entitlement ID');
        Assert::maxCount($this->entitlementIds, 100, 'You may specify a maximum of 100 entitlement IDs');
    }
}
