<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ConduitShardDisabled;

use DateTimeInterface;

final readonly class Transport
{
    /**
     * @param string                 $method         "websocket" or "webhook".
     * @param string|null            $callback       Optional. Webhook callback URL. Null if method is websocket.
     * @param string|null            $sessionId      Optional. WebSocket session ID. Null if method is webhook.
     * @param DateTimeInterface|null $connectedAt    Optional. Time the WebSocket session connected. Null if method
     *                                              is webhook.
     * @param DateTimeInterface|null $disconnectedAt Optional. Time the WebSocket session disconnected. Null if method
     *                                              is webhook.
     */
    public function __construct(
        public string $method,
        public ?string $callback = null,
        public ?string $sessionId = null,
        public ?DateTimeInterface $connectedAt = null,
        public ?DateTimeInterface $disconnectedAt = null,
    ) {
    }
}
