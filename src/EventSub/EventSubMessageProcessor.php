<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub;

use SimplyStream\TwitchApi\EventSub\Dedup\ProcessedMessageStore;
use SimplyStream\TwitchApi\EventSub\Http\EventSubHeaders;
use SimplyStream\TwitchApi\EventSub\Http\RawEventSubMessage;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubChallenge;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMessageInterface;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMessageType;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMetadata;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubNotification;
use SimplyStream\TwitchApi\EventSub\Registry\EventSubTypeRegistry;
use SimplyStream\TwitchApi\EventSub\Security\MessageFreshnessValidator;
use SimplyStream\TwitchApi\EventSub\Security\MessageSignatureVerifier;
use SimplyStream\TwitchApi\EventSub\Serialization\DenormalizerInterface;

final readonly class EventSubMessageProcessor
{
    public function __construct(
        private MessageSignatureVerifier $verifier,
        private MessageFreshnessValidator $freshness,
        private ProcessedMessageStore $processedMessages,
        private EventSubTypeRegistry $registry,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @param RawEventSubMessage $message
     *
     * @return EventSubMessageInterface
     * @throws \DateMalformedStringException
     * @throws \JsonException
     */
    public function process(RawEventSubMessage $message): EventSubMessageInterface
    {
        if (!$this->verifier->isValid($message)) {
            throw new InvalidSignatureException();
        }

        $metadata = $this->buildMetadata($message->headers);

        if (!$this->freshness->isFresh($metadata->timestamp)) {
            throw new StaleMessageException(
                sprintf('Message "%s" is outside the allowed time window.', $metadata->messageId)
            );
        }

        $body = json_decode($message->rawBody, true, flags: JSON_THROW_ON_ERROR);

        if ($metadata->messageType === EventSubMessageType::ChallengeVerification) {
            return $this->buildChallenge($metadata, $body);
        }

        if ($this->processedMessages->contains($metadata->messageId)) {
            throw new DuplicateMessageException(
                sprintf('Message "%s" has already been processed.', $metadata->messageId)
            );
        }
        $this->processedMessages->remember($metadata->messageId);

        return match ($metadata->messageType) {
            EventSubMessageType::Notification => $this->buildNotification($metadata, $body),
            default => throw new \LogicException(
                sprintf('Message type "%s" is not yet supported.', $metadata->messageType->value)
            ),
        };
    }

    private function buildMetadata(EventSubHeaders $headers): EventSubMetadata
    {
        return new EventSubMetadata(
            messageId: $headers->messageId(),
            messageType: EventSubMessageType::from($headers->messageType()),
            messageRetry: $headers->messageRetry(),
            timestamp: new \DateTimeImmutable($headers->timestamp()),
            subscriptionType: $headers->subscriptionType(),
            subscriptionVersion: $headers->subscriptionVersion(),
        );
    }

    /**
     * @param array<string, mixed> $body
     */
    private function buildChallenge(EventSubMetadata $metadata, array $body): EventSubChallenge
    {
        return new EventSubChallenge($metadata, $body['challenge']);
    }

    /**
     * @param EventSubMetadata     $metadata
     * @param array<string, mixed> $body
     *
     * @return EventSubNotification
     * @throws \DateMalformedStringException
     */
    private function buildNotification(EventSubMetadata $metadata, array $body): EventSubNotification
    {
        $classes = $this->registry->resolve(
            $metadata->subscriptionType,
            $metadata->subscriptionVersion,
        );

        $rawSubscription = $body['subscription'];

        $condition = $this->denormalizer->denormalize($rawSubscription['condition'], $classes['condition']);
        $event = $this->denormalizer->denormalize($body['event'], $classes['event']);
        $transport = new Transport(
            method: $rawSubscription['transport']['method'],
            callback: $rawSubscription['transport']['callback'] ?? null,
        );

        assert($condition instanceof ConditionInterface);
        assert($event instanceof EventInterface);

        $subscription = new Subscription(
            id: $rawSubscription['id'],
            status: $rawSubscription['status'],
            type: $rawSubscription['type'],
            version: $rawSubscription['version'],
            cost: $rawSubscription['cost'],
            condition: $condition,
            transport: $transport,
            createdAt: new \DateTimeImmutable($rawSubscription['created_at']),
        );

        return new EventSubNotification($metadata, $subscription, $event);
    }
}
