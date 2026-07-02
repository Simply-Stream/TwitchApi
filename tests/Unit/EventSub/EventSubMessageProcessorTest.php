<?php

namespace SimplyStream\TwitchApi\Tests\Unit\EventSub;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\EventSub\Clock\ClockInterface;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelFollowCondition;
use SimplyStream\TwitchApi\EventSub\Dedup\InMemoryProcessedMessageStore;
use SimplyStream\TwitchApi\EventSub\DuplicateMessageException;
use SimplyStream\TwitchApi\EventSub\Events\ChannelFollowEvent;
use SimplyStream\TwitchApi\EventSub\EventSubMessageProcessor;
use SimplyStream\TwitchApi\EventSub\Http\EventSubHeaders;
use SimplyStream\TwitchApi\EventSub\Http\RawEventSubMessage;
use SimplyStream\TwitchApi\EventSub\InvalidSignatureException;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubChallenge;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMessageType;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubNotification;
use SimplyStream\TwitchApi\EventSub\Registry\EventSubTypeRegistry;
use SimplyStream\TwitchApi\EventSub\Security\MessageFreshnessValidator;
use SimplyStream\TwitchApi\EventSub\Security\MessageSignatureVerifier;
use SimplyStream\TwitchApi\EventSub\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\EventSub\StaleMessageException;

final class EventSubMessageProcessorTest extends TestCase
{
    private const SECRET = 'test-secret-1234567890';
    private const TIMESTAMP = '2024-01-01T12:00:00Z';

    private EventSubMessageProcessor $processor;

    protected function setUp(): void
    {
        $clock = new class implements ClockInterface {
            public function now(): \DateTimeImmutable
            {
                return new \DateTimeImmutable('2024-01-01T12:00:30Z');
            }
        };

        $registry = new EventSubTypeRegistry();
        $registry->register('channel.follow', '1', ChannelFollowCondition::class, ChannelFollowEvent::class);

        $this->processor = new EventSubMessageProcessor(
            new MessageSignatureVerifier(self::SECRET),
            new MessageFreshnessValidator($clock),
            new InMemoryProcessedMessageStore(),
            $registry,
            $this->fakeDenormalizer(),
        );
    }

    #[Test]
    public function it_processes_a_challenge_and_returns_the_challenge_string(): void
    {
        $message = $this->rawMessage(
            body: $this->challengeBody(),
            messageType: EventSubMessageType::ChallengeVerification->value,
        );

        $result = $this->processor->process($message);

        self::assertInstanceOf(EventSubChallenge::class, $result);
        self::assertSame('pogchamp-kappa-360noscope-vohiyo', $result->challenge);
        self::assertSame(EventSubMessageType::ChallengeVerification, $result->metadata()->messageType);
    }

    #[Test]
    public function it_processes_a_notification_into_typed_objects(): void
    {
        $result = $this->processor->process($this->notificationMessage());

        self::assertInstanceOf(EventSubNotification::class, $result);

        self::assertInstanceOf(ChannelFollowEvent::class, $result->event);
        self::assertSame('awesome_user', $result->event->userLogin);
        self::assertSame('12826', $result->event->broadcasterUserId);
        self::assertEquals(
            new \DateTimeImmutable('2020-07-15T18:16:11.17106713Z'),
            $result->event->followedAt,
        );

        self::assertInstanceOf(ChannelFollowCondition::class, $result->subscription->condition);
        self::assertSame('12826', $result->subscription->condition->broadcasterUserId);

        self::assertSame('enabled', $result->subscription->status);
        self::assertSame('webhook', $result->subscription->transport->method);
        self::assertSame('https://example.com/webhooks/callback', $result->subscription->transport->callback);

        self::assertSame(EventSubMessageType::Notification, $result->metadata()->messageType);
        self::assertSame('channel.follow', $result->metadata()->subscriptionType);
        self::assertSame('1', $result->metadata()->subscriptionVersion);
    }

    #[Test]
    public function it_rejects_a_tampered_signature(): void
    {
        $message = new RawEventSubMessage(
            headers: EventSubHeaders::fromArray(
                $this->headers(EventSubMessageType::Notification->value) + [
                    'Twitch-Eventsub-Message-Signature' => 'sha256=deadbeef',
                ],
            ),
            rawBody: $this->notificationBody(),
        );

        $this->expectException(InvalidSignatureException::class);
        $this->processor->process($message);
    }

    #[Test]
    public function it_rejects_a_stale_message(): void
    {
        $message = $this->rawMessage(
            body: $this->notificationBody(),
            messageType: EventSubMessageType::Notification->value,
            timestamp: '2020-01-01T00:00:00Z',
        );

        $this->expectException(StaleMessageException::class);
        $this->processor->process($message);
    }

    #[Test]
    public function it_rejects_a_duplicate_notification(): void
    {
        $this->processor->process($this->notificationMessage());
        $this->expectException(DuplicateMessageException::class);
        $this->processor->process($this->notificationMessage());
    }

    #[Test]
    public function it_does_not_deduplicate_challenges(): void
    {
        $first = $this->processor->process($this->rawMessage($this->challengeBody(), EventSubMessageType::ChallengeVerification->value));
        $second = $this->processor->process($this->rawMessage($this->challengeBody(), EventSubMessageType::ChallengeVerification->value));

        self::assertInstanceOf(EventSubChallenge::class, $first);
        self::assertInstanceOf(EventSubChallenge::class, $second);
    }

    private function notificationMessage(): RawEventSubMessage
    {
        return $this->rawMessage($this->notificationBody(), EventSubMessageType::Notification->value);
    }

    private function rawMessage(string $body, string $messageType, string $timestamp = self::TIMESTAMP): RawEventSubMessage
    {
        return new RawEventSubMessage(
            headers: EventSubHeaders::fromArray($this->headers($messageType, $timestamp, $body)),
            rawBody: $body,
        );
    }

    /**
     * @return array<string, string>
     */
    private function headers(string $messageType, string $timestamp = self::TIMESTAMP, string $body = ''): array
    {
        $messageId = 'test-message-id-0001';

        return [
            'Twitch-Eventsub-Message-Id' => $messageId,
            'Twitch-Eventsub-Message-Retry' => '0',
            'Twitch-Eventsub-Message-Type' => $messageType,
            'Twitch-Eventsub-Message-Signature' => $this->sign($messageId, $timestamp, $body),
            'Twitch-Eventsub-Message-Timestamp' => $timestamp,
            'Twitch-Eventsub-Subscription-Type' => 'channel.follow',
            'Twitch-Eventsub-Subscription-Version' => '1',
        ];
    }

    private function sign(string $messageId, string $timestamp, string $body): string
    {
        return 'sha256=' . hash_hmac('sha256', $messageId . $timestamp . $body, self::SECRET);
    }

    private function fakeDenormalizer(): DenormalizerInterface
    {
        return new class implements DenormalizerInterface {
            public function denormalize(array $data, string $type): object
            {
                return match ($type) {
                    ChannelFollowCondition::class => new ChannelFollowCondition(
                        broadcasterUserId: $data['broadcaster_user_id'],
                    ),
                    ChannelFollowEvent::class => new ChannelFollowEvent(
                        userId: $data['user_id'],
                        userLogin: $data['user_login'],
                        userName: $data['user_name'],
                        broadcasterUserId: $data['broadcaster_user_id'],
                        broadcasterUserLogin: $data['broadcaster_user_login'],
                        broadcasterUserName: $data['broadcaster_user_name'],
                        followedAt: new \DateTimeImmutable($data['followed_at']),
                    ),
                    default => throw new \LogicException("Unhandled type in test: {$type}"),
                };
            }
        };
    }

    private function notificationBody(): string
    {
        return <<<'JSON'
        {
          "subscription": {
            "id": "f1c2a387-161a-49f9-a165-0f21d7a4e1c4",
            "status": "enabled",
            "type": "channel.follow",
            "version": "1",
            "cost": 1,
            "condition": { "broadcaster_user_id": "12826" },
            "transport": {
              "method": "webhook",
              "callback": "https://example.com/webhooks/callback"
            },
            "created_at": "2019-11-16T10:11:12.634234626Z"
          },
          "event": {
            "user_id": "1337",
            "user_login": "awesome_user",
            "user_name": "Awesome_User",
            "broadcaster_user_id": "12826",
            "broadcaster_user_login": "twitch",
            "broadcaster_user_name": "Twitch",
            "followed_at": "2020-07-15T18:16:11.17106713Z"
          }
        }
        JSON;
    }

    private function challengeBody(): string
    {
        return <<<'JSON'
        {
          "challenge": "pogchamp-kappa-360noscope-vohiyo",
          "subscription": {
            "id": "f1c2a387-161a-49f9-a165-0f21d7a4e1c4",
            "status": "webhook_callback_verification_pending",
            "type": "channel.follow",
            "version": "1",
            "cost": 1,
            "condition": { "broadcaster_user_id": "12826" },
            "transport": {
              "method": "webhook",
              "callback": "https://example.com/webhooks/callback"
            },
            "created_at": "2019-11-16T10:11:12.634234626Z"
          }
        }
        JSON;
    }
}
