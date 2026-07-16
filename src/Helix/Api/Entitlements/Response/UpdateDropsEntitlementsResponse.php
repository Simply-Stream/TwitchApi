<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Entitlements\Response;

use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlementUpdate;

final readonly class UpdateDropsEntitlementsResponse
{
    /** @param list<DropEntitlementUpdate> $data */
    public function __construct(
        public array $data,
    ) {
    }
}
