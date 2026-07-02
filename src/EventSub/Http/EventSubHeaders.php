<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Http;

final class EventSubHeaders
{
    private const string MESSAGE_ID = 'twitch-eventsub-message-id';
    private const string MESSAGE_RETRY = 'twitch-eventsub-message-retry';
    private const string MESSAGE_TYPE = 'twitch-eventsub-message-type';
    private const string MESSAGE_SIGNATURE = 'twitch-eventsub-message-signature';
    private const string MESSAGE_TIMESTAMP = 'twitch-eventsub-message-timestamp';
    private const string SUBSCRIPTION_TYPE = 'twitch-eventsub-subscription-type';
    private const string SUBSCRIPTION_VERSION = 'twitch-eventsub-subscription-version';

    /** @param array<string, string> $normalized */
    private function __construct(private readonly array $normalized)
    {
    }

    /**
     * @param array<string, string|string[]> $headers
     */
    public static function fromArray(array $headers): self
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            $normalized[strtolower($name)] = is_array($value) ? ($value[0] ?? '') : $value;
        }

        return new self($normalized);
    }

    public function messageId(): string
    {
        return $this->require(self::MESSAGE_ID);
    }

    public function messageType(): string
    {
        return $this->require(self::MESSAGE_TYPE);
    }

    public function signature(): string
    {
        return $this->require(self::MESSAGE_SIGNATURE);
    }

    public function timestamp(): string
    {
        return $this->require(self::MESSAGE_TIMESTAMP);
    }

    public function subscriptionType(): string
    {
        return $this->require(self::SUBSCRIPTION_TYPE);
    }

    public function subscriptionVersion(): string
    {
        return $this->require(self::SUBSCRIPTION_VERSION);
    }

    public function messageRetry(): int
    {
        return (int) ($this->normalized[self::MESSAGE_RETRY] ?? 0);
    }

    private function require(string $key): string
    {
        return $this->normalized[$key]
            ?? throw new \RuntimeException("Missing required EventSub header: {$key}");
    }
}
