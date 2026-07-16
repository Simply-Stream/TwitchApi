<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub;

use SimplyStream\TwitchApi\EventSub\Dedup\ProcessedMessageStoreInterface;
use SimplyStream\TwitchApi\EventSub\Http\EventSubHeaders;
use SimplyStream\TwitchApi\EventSub\Http\RawEventSubMessage;
use SimplyStream\TwitchApi\EventSub\Messages\BatchedEvent;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubBatchNotification;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubChallenge;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMessageInterface;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMessageType;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMetadata;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubNotification;
use SimplyStream\TwitchApi\EventSub\Registry\EventSubTypeRegistry;
use SimplyStream\TwitchApi\EventSub\Security\MessageFreshnessValidator;
use SimplyStream\TwitchApi\EventSub\Security\MessageSignatureVerifier;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;

final readonly class EventSubMessageProcessor
{
    public function __construct(
        private MessageSignatureVerifier $verifier,
        private MessageFreshnessValidator $freshness,
        private ProcessedMessageStoreInterface $processedMessages,
        private EventSubTypeRegistry $registry,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
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
     *
     * @throws \DateMalformedStringException
     */
    private function buildChallenge(EventSubMetadata $metadata, array $body): EventSubChallenge
    {
        $classes = $this->registry->resolve(
            $metadata->subscriptionType,
            $metadata->subscriptionVersion,
        );

        $subscription = $this->buildSubscription($body['subscription'], $classes['condition']);

        return new EventSubChallenge($metadata, $subscription, $body['challenge']);
    }

    /**
     * @param array<string, mixed> $body
     *
     * @throws \DateMalformedStringException
     */
    private function buildNotification(EventSubMetadata $metadata, array $body): EventSubMessageInterface
    {
        $classes = $this->registry->resolve(
            $metadata->subscriptionType,
            $metadata->subscriptionVersion,
        );

        $subscription = $this->buildSubscription($body['subscription'], $classes['condition']);

        if (array_key_exists('events', $body)) {
            $events = [];
            foreach ($body['events'] as $rawEvent) {
                $event = $this->denormalizer->denormalize($rawEvent['data'], $classes['event']);
                assert($event instanceof EventInterface);
                $events[] = new BatchedEvent(id: $rawEvent['id'], event: $event);
            }

            return new EventSubBatchNotification($metadata, $subscription, $events);
        }

        $event = $this->denormalizer->denormalize($body['event'], $classes['event']);
        assert($event instanceof EventInterface);

        return new EventSubNotification($metadata, $subscription, $event);
    }

    /**
     * @param array<string, mixed>             $rawSubscription
     * @param class-string<ConditionInterface> $conditionClass
     *
     * @throws \DateMalformedStringException
     */
    private function buildSubscription(array $rawSubscription, string $conditionClass): Subscription
    {
        $condition = $this->denormalizer->denormalize($rawSubscription['condition'], $conditionClass);
        assert($condition instanceof ConditionInterface);

        $transport = new Transport(
            method: $rawSubscription['transport']['method'],
            callback: $rawSubscription['transport']['callback'] ?? null,
        );

        return new Subscription(
            id: $rawSubscription['id'],
            status: $rawSubscription['status'],
            type: $rawSubscription['type'],
            version: $rawSubscription['version'],
            cost: $rawSubscription['cost'],
            condition: $condition,
            transport: $transport,
            createdAt: new \DateTimeImmutable($rawSubscription['created_at']),
        );
    }
}
