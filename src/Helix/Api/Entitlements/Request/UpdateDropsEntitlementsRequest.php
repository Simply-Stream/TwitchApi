<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Entitlements\Request;

use SimplyStream\TwitchApi\Helix\Models\Entitlements\UpdateDropEntitlement;

final readonly class UpdateDropsEntitlementsRequest
{
    /**
     * @param UpdateDropEntitlement $entitlement The entitlement update payload (entitlement ids + fulfillment status).
     */
    public function __construct(
        public UpdateDropEntitlement $entitlement,
    ) {}
}
