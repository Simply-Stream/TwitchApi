<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Ads;

final readonly class Commercial
{
    /**
     * @param int    $length     The length of the commercial you requested. If you request a commercial that’s longer
     *                           than 180 seconds, the API uses 180 seconds.
     * @param string $message    A message that indicates whether Twitch was able to serve an ad.
     * @param int    $retryAfter The number of seconds you must wait before running another commercial.
     */
    public function __construct(
        public int $length,
        public string $message,
        public int $retryAfter,
    ) {
    }
}
