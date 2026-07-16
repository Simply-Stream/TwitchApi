<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use SimplyStream\TwitchApi\Helix\Models\Moderation\ManageHeldAutoModMessage;

final readonly class ManageHeldAutoModMessageRequest
{
    /**
     * @param ManageHeldAutoModMessage $message The held message action to perform.
     */
    public function __construct(
        public ManageHeldAutoModMessage $message,
    ) {
    }
}
