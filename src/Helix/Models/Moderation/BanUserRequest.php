<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;

final readonly class BanUserRequest extends AbstractModel
{
    /**
     * @param BanUser $data Identifies the user and type of ban.
     */
    public function __construct(
        private BanUser $data
    ) {
    }

    public function getData(): BanUser
    {
        return $this->data;
    }
}
