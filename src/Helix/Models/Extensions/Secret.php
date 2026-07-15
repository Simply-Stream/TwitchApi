<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

use DateTimeInterface;

final readonly class Secret
{
    /**
     * @param string            $content    The raw secret that you use with JWT encoding.
     * @param DateTimeInterface $activeAt   The UTC date and time (in RFC3339 format) that you may begin using this
     *                                      secret to sign a JWT.
     * @param DateTimeInterface $expiresAt  The UTC date and time (in RFC3339 format) that you must stop using this
     *                                      secret to decode a JWT.
     */
    public function __construct(
        public string $content,
        public DateTimeInterface $activeAt,
        public DateTimeInterface $expiresAt
    ) {
    }
}
