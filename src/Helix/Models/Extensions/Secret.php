<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Secret
{
    use SerializesModels;

    /**
     * @param string            $content    The raw secret that you use with JWT encoding.
     * @param DateTimeInterface $activeAt   The UTC date and time (in RFC3339 format) that you may begin using this
     *                                      secret to sign a JWT.
     * @param DateTimeInterface $expiresAt  The UTC date and time (in RFC3339 format) that you must stop using this
     *                                      secret to decode a JWT.
     */
    public function __construct(
        private string $content,
        private DateTimeInterface $activeAt,
        private DateTimeInterface $expiresAt
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getActiveAt(): DateTimeInterface
    {
        return $this->activeAt;
    }

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }
}
