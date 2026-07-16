<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Security;

use SimplyStream\TwitchApi\EventSub\Http\RawEventSubMessage;

final readonly class MessageSignatureVerifier
{
    public function __construct(private string $secret)
    {
    }

    public function isValid(RawEventSubMessage $message): bool
    {
        $headers = $message->headers;
        $payload = $headers->messageId() . $headers->timestamp() . $message->rawBody;
        $expected = 'sha256=' . hash_hmac('sha256', $payload, $this->secret);

        return hash_equals($expected, $headers->signature());
    }
}
