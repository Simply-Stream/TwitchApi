<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub;

use DateTimeInterface;

final readonly class Transport
{
    /**
     * @param string                 $method         The transport method. Possible values are:
     *                                               - webhook
     *                                               - websocket
     *                                               - conduit
     * @param string|null            $secret         The webhook secret, used to verify incoming events
     * @param string|null            $callback       The callback URL where the notifications are sent. Specify only
     *                                               for webhook transports.
     * @param string|null            $sessionId      An ID that identifies the WebSocket to send notifications to.
     *                                               Specify only for websocket transports.
     * @param string|null            $conduitId      An ID that identifies the conduit to send notifications to.
     *                                               Specify only for conduit transports.
     * @param DateTimeInterface|null $connectedAt    The UTC date and time (in RFC3339 format) of when the WebSocket
     *                                               connection was established. Returned only for websocket
     *                                               transports.
     * @param DateTimeInterface|null $disconnectedAt The UTC date and time (in RFC3339 format) of when the WebSocket
     *                                               connection was lost. Returned only for websocket transports.
     */
    public function __construct(
        public string $method,
        public ?string $secret = null,
        public ?string $callback = null,
        public ?string $sessionId = null,
        public ?string $conduitId = null,
        public ?DateTimeInterface $connectedAt = null,
        public ?DateTimeInterface $disconnectedAt = null,
    ) {
    }
}
