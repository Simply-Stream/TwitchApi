<?php

namespace SimplyStream\TwitchApi\Tests\Functional\EventSub;

use Closure;
use DateTimeImmutable;
use FilesystemIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SimplyStream\TwitchApi\EventSub\Clock\ClockInterface;
use SimplyStream\TwitchApi\EventSub\Dedup\InMemoryProcessedMessageStore;
use SimplyStream\TwitchApi\EventSub\Events\ChannelAdBreakBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelBanEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelCheerEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelFollowEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelGoalBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelModeratorAddEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelModeratorRemoveEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsCustomRewardAddEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsCustomRewardRedemptionAddEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsCustomRewardRedemptionUpdateEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsCustomRewardRemoveEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPointsCustomRewardUpdateEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPollBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPollEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPollProgressEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPredictionBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPredictionEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPredictionLockEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelPredictionProgressEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelRaidEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelShieldModeBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelShieldModeEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscribeEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscriptionEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscriptionGiftEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscriptionMessageEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelUnbanEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelUnbanRequestCreateEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelUnbanRequestResolveEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelUpdateEvent;
use SimplyStream\TwitchApi\EventSub\Events\CharityCampaignDonateEvent;
use SimplyStream\TwitchApi\EventSub\Events\CharityCampaignProgressEvent;
use SimplyStream\TwitchApi\EventSub\Events\CharityCampaignStartEvent;
use SimplyStream\TwitchApi\EventSub\Events\CharityCampaignStopEvent;
use SimplyStream\TwitchApi\EventSub\Events\DropEntitlementGrantEvent;
use SimplyStream\TwitchApi\EventSub\Events\ExtensionBitsTransactionCreateEvent;
use SimplyStream\TwitchApi\EventSub\Events\GoalBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\GoalEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\GoalProgressEvent;
use SimplyStream\TwitchApi\EventSub\Events\HypeTrainBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\HypeTrainEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\HypeTrainProgressEvent;
use SimplyStream\TwitchApi\EventSub\Events\ShoutoutCreateEvent;
use SimplyStream\TwitchApi\EventSub\Events\ShoutoutReceiveEvent;
use SimplyStream\TwitchApi\EventSub\Events\StreamOfflineEvent;
use SimplyStream\TwitchApi\EventSub\Events\StreamOnlineEvent;
use SimplyStream\TwitchApi\EventSub\Events\UserAuthorizationGrantEvent;
use SimplyStream\TwitchApi\EventSub\Events\UserAuthorizationRevokeEvent;
use SimplyStream\TwitchApi\EventSub\Events\UserUpdateEvent;
use SimplyStream\TwitchApi\EventSub\EventSubMessageProcessor;
use SimplyStream\TwitchApi\EventSub\Http\EventSubHeaders;
use SimplyStream\TwitchApi\EventSub\Http\RawEventSubMessage;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubNotification;
use SimplyStream\TwitchApi\EventSub\Registry\EventSubTypeRegistryBuilder;
use SimplyStream\TwitchApi\EventSub\Security\MessageFreshnessValidator;
use SimplyStream\TwitchApi\EventSub\Security\MessageSignatureVerifier;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\EmptyStringAwareDateTimeNormalizer;
use SimplyStream\TwitchApi\Tests\Helper\TwitchNameConverter;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use function dirname;

final class EventSubMessageProcessorRoundtripTest extends TestCase
{
    // Must match the secret passed to `twitch event trigger --secret ...`
    // when the fixtures were captured.
    private const SECRET = '1234567890';

    #[Test]
    #[DataProvider('messages')]
    public function it_processes_incoming_messages(
        string $fixtureFile,
        Closure $assertions,
    ): void {
        [$headers, $body] = self::fixture($fixtureFile);

        $timestamp = $headers['Twitch-Eventsub-MESSAGE-TIMESTAMP']
            ?? self::fail("Fixture {$fixtureFile} has no Twitch-Eventsub-Message-Timestamp header.");

        $processor = $this->buildProcessor(new DateTimeImmutable($timestamp));

        $raw = new RawEventSubMessage(
            headers: EventSubHeaders::fromArray($headers),
            rawBody: $body,
        );

        $result = $processor->process($raw);

        $assertions($result);
    }

    private function buildProcessor(DateTimeImmutable $now): EventSubMessageProcessor
    {
        $registry = new EventSubTypeRegistryBuilder()->build(self::discoverEventClasses());

        $clock = new class ($now) implements ClockInterface {
            public function __construct(private readonly DateTimeImmutable $now)
            {
            }

            public function now(): DateTimeImmutable
            {
                return $this->now;
            }
        };

        return new EventSubMessageProcessor(
            new MessageSignatureVerifier(self::SECRET),
            new MessageFreshnessValidator($clock),
            new InMemoryProcessedMessageStore(),
            $registry,
            $this->buildDenormalizer(),
        );
    }

    private function buildDenormalizer(): DenormalizerInterface
    {
        $extractor = new PropertyInfoExtractor(
            typeExtractors: [new PhpDocExtractor(), new ReflectionExtractor()],
        );

        $symfony = new Serializer([
            new BackedEnumNormalizer(),
            new EmptyStringAwareDateTimeNormalizer(new DateTimeNormalizer()),
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                nameConverter: new TwitchNameConverter(
                    new CamelCaseToSnakeCaseNameConverter(),
                ),
                propertyTypeExtractor: $extractor,
                defaultContext: [ObjectNormalizer::SKIP_NULL_VALUES => true],
            ),
        ]);

        return new class ($symfony) implements DenormalizerInterface {
            public function __construct(
                private readonly \Symfony\Component\Serializer\Normalizer\DenormalizerInterface $inner,
            ) {}

            public function denormalize(array $data, string $type): object
            {
                return $this->inner->denormalize($data, $type);
            }
        };
    }

    private static function discoverEventClasses(): array
    {
        $base = 'SimplyStream\\TwitchApi\\EventSub\\Events\\';
        $root = dirname(__DIR__, 3) . '/src/EventSub/Events';

        $classes = [];
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
        );
        foreach ($it as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $relative = substr($file->getPathname(), strlen($root) + 1, -4);
            $classes[] = $base . str_replace('/', '\\', $relative);
        }

        return $classes;
    }

    /**
     * @return iterable<string, array{string, Closure}>
     */
    public static function messages(): iterable
    {
        yield 'channel.ad_break.begin notification' => [
            'channel.ad_break.begin-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelAdBreakBeginEvent::class, $result->event);
                self::assertSame('channel.ad_break.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.ban notification' => [
            'channel.ban-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelBanEvent::class, $result->event);
                self::assertSame('channel.ban', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward.add notification' => [
            'channel.channel_points_custom_reward.add-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardAddEvent::class, $result->event);
                self::assertSame('channel.channel_points_custom_reward.add', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward.remove notification' => [
            'channel.channel_points_custom_reward.remove-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardRemoveEvent::class, $result->event);
                self::assertSame('channel.channel_points_custom_reward.remove', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward.update notification' => [
            'channel.channel_points_custom_reward.update-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardUpdateEvent::class, $result->event);
                self::assertSame('channel.channel_points_custom_reward.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward_redemption.add notification' => [
            'channel.channel_points_custom_reward_redemption.add-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardRedemptionAddEvent::class, $result->event);
                self::assertSame('channel.channel_points_custom_reward_redemption.add', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward_redemption.update notification' => [
            'channel.channel_points_custom_reward_redemption.update-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardRedemptionUpdateEvent::class, $result->event);
                self::assertSame('channel.channel_points_custom_reward_redemption.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.donate notification' => [
            'channel.charity_campaign.donate-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignDonateEvent::class, $result->event);
                self::assertSame('channel.charity_campaign.donate', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.progress notification' => [
            'channel.charity_campaign.progress-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignProgressEvent::class, $result->event);
                self::assertSame('channel.charity_campaign.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.start notification' => [
            'channel.charity_campaign.start-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignStartEvent::class, $result->event);
                self::assertSame('channel.charity_campaign.start', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.stop notification' => [
            'channel.charity_campaign.stop-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignStopEvent::class, $result->event);
                self::assertSame('channel.charity_campaign.stop', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.cheer notification' => [
            'channel.cheer-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelCheerEvent::class, $result->event);
                self::assertSame('channel.cheer', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.follow notification' => [
            'channel.follow-2-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelFollowEvent::class, $result->event);
                self::assertSame('channel.follow', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.goal.begin notification' => [
            'channel.goal.begin-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(GoalBeginEvent::class, $result->event);
                self::assertSame('channel.goal.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.goal.end notification' => [
            'channel.goal.end-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(GoalEndEvent::class, $result->event);
                self::assertSame('channel.goal.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.goal.progress notification' => [
            'channel.goal.progress-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(GoalProgressEvent::class, $result->event);
                self::assertSame('channel.goal.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        // Filename says "-1-", not "-2-" — worth double-checking against the docs
        // whether hype_train v2 exists at all before relying on this in production.
        yield 'channel.hype_train.begin notification' => [
            'channel.hype_train.begin-2-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(HypeTrainBeginEvent::class, $result->event);
                self::assertSame('channel.hype_train.begin', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.hype_train.end notification' => [
            'channel.hype_train.end-2-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(HypeTrainEndEvent::class, $result->event);
                self::assertSame('channel.hype_train.end', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.hype_train.progress notification' => [
            'channel.hype_train.progress-2-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(HypeTrainProgressEvent::class, $result->event);
                self::assertSame('channel.hype_train.progress', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.moderator.add notification' => [
            'channel.moderator.add-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelModeratorAddEvent::class, $result->event);
                self::assertSame('channel.moderator.add', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.moderator.remove notification' => [
            'channel.moderator.remove-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelModeratorRemoveEvent::class, $result->event);
                self::assertSame('channel.moderator.remove', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.poll.begin notification' => [
            'channel.poll.begin-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPollBeginEvent::class, $result->event);
                self::assertSame('channel.poll.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.poll.end notification' => [
            'channel.poll.end-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPollEndEvent::class, $result->event);
                self::assertSame('channel.poll.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.poll.progress notification' => [
            'channel.poll.progress-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPollProgressEvent::class, $result->event);
                self::assertSame('channel.poll.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.begin notification' => [
            'channel.prediction.begin-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionBeginEvent::class, $result->event);
                self::assertSame('channel.prediction.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.end notification' => [
            'channel.prediction.end-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionEndEvent::class, $result->event);
                self::assertSame('channel.prediction.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.lock notification' => [
            'channel.prediction.lock-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionLockEvent::class, $result->event);
                self::assertSame('channel.prediction.lock', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.progress notification' => [
            'channel.prediction.progress-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionProgressEvent::class, $result->event);
                self::assertSame('channel.prediction.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.raid notification' => [
            'channel.raid-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelRaidEvent::class, $result->event);
                self::assertSame('channel.raid', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.shield_mode.begin notification' => [
            'channel.shield_mode.begin-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelShieldModeBeginEvent::class, $result->event);
                self::assertSame('channel.shield_mode.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.shield_mode.end notification' => [
            'channel.shield_mode.end-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelShieldModeEndEvent::class, $result->event);
                self::assertSame('channel.shield_mode.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.shoutout.create notification' => [
            'channel.shoutout.create-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ShoutoutCreateEvent::class, $result->event);
                self::assertSame('channel.shoutout.create', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.shoutout.receive notification' => [
            'channel.shoutout.receive-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ShoutoutReceiveEvent::class, $result->event);
                self::assertSame('channel.shoutout.receive', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscribe notification' => [
            'channel.subscribe-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscribeEvent::class, $result->event);
                self::assertSame('channel.subscribe', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscription.end notification' => [
            'channel.subscription.end-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscriptionEndEvent::class, $result->event);
                self::assertSame('channel.subscription.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscription.gift notification' => [
            'channel.subscription.gift-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscriptionGiftEvent::class, $result->event);
                self::assertSame('channel.subscription.gift', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscription.message notification' => [
            'channel.subscription.message-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscriptionMessageEvent::class, $result->event);
                self::assertSame('channel.subscription.message', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.unban notification' => [
            'channel.unban-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelUnbanEvent::class, $result->event);
                self::assertSame('channel.unban', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.unban_request.create notification' => [
            'channel.unban_request.create-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelUnbanRequestCreateEvent::class, $result->event);
                self::assertSame('channel.unban_request.create', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.unban_request.resolve notification' => [
            'channel.unban_request.resolve-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelUnbanRequestResolveEvent::class, $result->event);
                self::assertSame('channel.unban_request.resolve', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.update notification' => [
            'channel.update-2-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelUpdateEvent::class, $result->event);
                self::assertSame('channel.update', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'drop.entitlement.grant notification' => [
            'drop.entitlement.grant-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(DropEntitlementGrantEvent::class, $result->event);
                self::assertSame('drop.entitlement.grant', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'extension.bits_transaction.create notification' => [
            'extension.bits_transaction.create-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ExtensionBitsTransactionCreateEvent::class, $result->event);
                self::assertSame('extension.bits_transaction.create', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'stream.offline notification' => [
            'stream.offline-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(StreamOfflineEvent::class, $result->event);
                self::assertSame('stream.offline', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'stream.online notification' => [
            'stream.online-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(StreamOnlineEvent::class, $result->event);
                self::assertSame('stream.online', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'user.authorization.grant notification' => [
            'user.authorization.grant-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(UserAuthorizationGrantEvent::class, $result->event);
                self::assertSame('user.authorization.grant', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'user.authorization.revoke notification' => [
            'user.authorization.revoke-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(UserAuthorizationRevokeEvent::class, $result->event);
                self::assertSame('user.authorization.revoke', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'user.update notification' => [
            'user.update-1-notification.json',
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(UserUpdateEvent::class, $result->event);
                self::assertSame('user.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];
    }

    /**
     * @return array{0: array<string,string>, 1: string}
     */
    private static function fixture(string $file): array
    {
        $decoded = json_decode(
            file_get_contents(__DIR__ . '/Fixtures/' . $file),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        return [$decoded['headers'], $decoded['body']];
    }
}
