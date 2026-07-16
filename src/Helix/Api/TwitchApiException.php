<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

final class TwitchApiException extends \RuntimeException
{
    /**
     * @param array<string, mixed> $payload  Decodierter Twitch-Error-Body
     */
    private function __construct(
        public readonly int $status,
        public readonly ?string $error,
        string $message,
        public readonly array $payload,
    ) {
        parent::__construct($message, $status);
    }

    public static function fromResponse(int $status, string $raw): self
    {
        $payload = [];
        if ($raw !== '') {
            try {
                $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
                if (is_array($decoded)) {
                    $payload = $decoded;
                }
            } catch (\JsonException) {
                // Body war kein JSON – Rohtext als Message unten
            }
        }

        $error = null;
        if (isset($payload['error']) && is_string($payload['error'])) {
            $error = $payload['error'];
        }

        $message = 'Twitch API request failed';
        if ($raw !== '') {
            $message = $raw;
        }
        if (isset($payload['message']) && is_string($payload['message'])) {
            $message = $payload['message'];
        }

        return new self(
            status: $status,
            error: $error,
            message: $message,
            payload: $payload,
        );
    }
}
