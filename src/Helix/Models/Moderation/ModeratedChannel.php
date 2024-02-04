<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;

final readonly class ModeratedChannel extends AbstractModel
{
    /**
     * @param string $broadcasterId    An ID that uniquely identifies the channel this user can moderate.
     * @param string $broadcasterLogin The channel’s login name.
     * @param string $broadcasterName  The channels’ display name.
     */
    public function __construct(
        private string $broadcasterId,
        private string $broadcasterLogin,
        private string $broadcasterName,
    ) {
    }

    public function getBroadcasterId(): string
    {
        return $this->broadcasterId;
    }

    public function getBroadcasterName(): string
    {
        return $this->broadcasterName;
    }

    public function getBroadcasterLogin(): string
    {
        return $this->broadcasterLogin;
    }
}
