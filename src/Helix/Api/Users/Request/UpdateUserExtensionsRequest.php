<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

use SimplyStream\TwitchApi\Helix\Models\Users\UpdateUserExtension;

final readonly class UpdateUserExtensionsRequest
{
    /**
     * @param UpdateUserExtension $extensions The extensions to update, keyed by configuration type.
     */
    public function __construct(
        public UpdateUserExtension $extensions,
    ) {
    }
}
