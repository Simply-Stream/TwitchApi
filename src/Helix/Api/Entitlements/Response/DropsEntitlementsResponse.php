<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Entitlements\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlement;

final readonly class DropsEntitlementsResponse
{
    /** @param list<DropEntitlement> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}
