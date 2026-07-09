<?php

namespace SimplyStream\TwitchApi\Tests\Functional\EventSub;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\EventSub\Clock\ClockInterface;
use SimplyStream\TwitchApi\EventSub\Dedup\InMemoryProcessedMessageStore;
use SimplyStream\TwitchApi\EventSub\Events\AutomodMessageHoldEvent;
use SimplyStream\TwitchApi\EventSub\Events\AutomodMessageHoldV1Event;
use SimplyStream\TwitchApi\EventSub\Events\AutomodMessageUpdateV1Event;
use SimplyStream\TwitchApi\EventSub\Events\ChannelAdBreakBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelBanEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelChatClearEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelChatClearUserMessagesEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelChatMessageDeleteEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelChatMessageEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotificationEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelChatSettingsUpdateEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelCheerEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelFollowEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelGuestStarGuestUpdateEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelGuestStarSessionBeginEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelGuestStarSessionEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelGuestStarSettingsUpdateEvent;
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
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscribeEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscriptionEndEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscriptionGiftEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelSubscriptionMessageEvent;
use SimplyStream\TwitchApi\EventSub\Events\ChannelUnbanEvent;
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
use SimplyStream\TwitchApi\EventSub\Messages\EventSubMetadata;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubNotification;
use SimplyStream\TwitchApi\EventSub\Registry\EventSubTypeRegistryBuilder;
use SimplyStream\TwitchApi\EventSub\Security\MessageFreshnessValidator;
use SimplyStream\TwitchApi\EventSub\Security\MessageSignatureVerifier;
use SimplyStream\TwitchApi\EventSub\Shared\Message;
use SimplyStream\TwitchApi\EventSub\Shared\MessageFragment;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class EventSubMessageProcessorRoundtripTest extends TestCase
{
    private const SECRET = 'test-secret-1234567890';

    private EventSubMessageProcessor $processor;

    protected function setUp(): void
    {
        $registry = new EventSubTypeRegistryBuilder()->build(self::discoverEventClasses());

        $clock = new class implements ClockInterface {
            public function now(): \DateTimeImmutable
            {
                return new \DateTimeImmutable('2024-01-01T12:00:30Z');
            }
        };

        $this->processor = new EventSubMessageProcessor(
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
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                nameConverter: new CamelCaseToSnakeCaseNameConverter(),
                propertyTypeExtractor: $extractor,
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
        $root = \dirname(__DIR__, 3) . '/src/EventSub/Events';

        $classes = [];
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS),
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

    #[Test]
    #[DataProvider('messages')]
    public function it_processes_incoming_messages(
        array $headers,
        string $body,
        \Closure $assertions,
    ): void {
        $headers['Twitch-Eventsub-Message-Signature'] = $this->sign(
            $headers['Twitch-Eventsub-Message-Id'],
            $headers['Twitch-Eventsub-Message-Timestamp'],
            $body,
        );

        $raw = new RawEventSubMessage(
            headers: EventSubHeaders::fromArray($headers),
            rawBody: $body,
        );

        $result = $this->processor->process($raw);

        $assertions($result);
    }

    /**
     * @return iterable<string, array{array<string,string>, string, \Closure}>
     */
    public static function messages(): iterable
    {
        yield 'automod.message.hold notification' => [
            self::headers('notification', 'automod.message.hold', '1'),
            self::body('automod-message-hold-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);

                $event = $result->event;
                self::assertInstanceOf(AutomodMessageHoldV1Event::class, $event);
                self::assertSame('1337', $event->broadcasterUserId);
                self::assertSame('blahblah', $event->broadcasterUserLogin);
                self::assertSame('blah', $event->broadcasterUserName);
                self::assertSame('456789012', $event->userId);
                self::assertSame('baduserbla', $event->userLogin);
                self::assertSame('baduser', $event->userName);
                self::assertSame('bad-message-id', $event->messageId);

                $message = $event->message;
                self::assertInstanceOf(Message::class, $message);
                self::assertSame('This is a bad message…', $message->text);
                self::assertIsArray($message->fragments);
                self::assertContainsOnlyInstancesOf(MessageFragment::class, $message->fragments);

                $eventSubMetadata = $result->metadata();
                self::assertInstanceOf(EventSubMetadata::class, $eventSubMetadata);
                self::assertSame('automod.message.hold', $eventSubMetadata->subscriptionType);
                self::assertSame('1', $eventSubMetadata->subscriptionVersion);
                self::assertSame('id-automod.message.hold-notification', $eventSubMetadata->messageId);
            },
        ];

        yield 'automod.message.hold notification #2' => [
            self::headers('notification', 'automod.message.hold', '2'),
            self::body('automod-message-hold-v2-2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);

                $event = $result->event;
                self::assertInstanceOf(AutomodMessageHoldEvent::class, $event);
                self::assertSame('1337', $event->broadcasterUserId);
                self::assertSame('blah', $event->broadcasterUserLogin);
                self::assertSame('blahblah', $event->broadcasterUserName);
                self::assertSame('4242', $event->userId);
                self::assertSame('baduser', $event->userLogin);
                self::assertSame('badbaduser', $event->userName);
                self::assertSame('bad-message-id', $event->messageId);

                $message = $event->message;
                self::assertInstanceOf(Message::class, $message);
                self::assertSame('This is a bad message… pogchamp', $message->text);
                self::assertIsArray($message->fragments);
                self::assertContainsOnlyInstancesOf(MessageFragment::class, $message->fragments);

                $eventSubMetadata = $result->metadata();
                self::assertInstanceOf(EventSubMetadata::class, $eventSubMetadata);
                self::assertSame('id-automod.message.hold-notification', $eventSubMetadata->messageId);
                self::assertSame('automod.message.hold', $eventSubMetadata->subscriptionType);
                self::assertSame('2', $eventSubMetadata->subscriptionVersion);
            },
        ];

        yield 'automod.message.hold notification #3' => [
            self::headers('notification', 'automod.message.hold', '2'),
            self::body('automod-message-hold-v2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(AutomodMessageHoldEvent::class, $result->event);

                self::assertSame('automod.message.hold', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'automod.message.update notification' => [
            self::headers('notification', 'automod.message.update', '1'),
            self::body('automod-message-update-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(AutomodMessageUpdateV1Event::class, $result->event);

                self::assertSame('automod.message.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'automod.settings.update notification' => [
//            self::headers('notification', 'automod.settings.update', '1'),
//            self::body('automod-settings-update-v1-nested-data.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(AutomodSettingsUpdateEvent::class, $result->event);
//            },
//        ];

//        yield 'automod.terms.update notification' => [
//            self::headers('notification', 'automod.terms.update', '1'),
//            self::body('automod-terms-update-v1.json'),
//            function (mixed $result): void {
//                self::markTestIncomplete('AutomodTermsUpdateEvent not yet implemented');
//            },
//        ];

        yield 'channel.ad_break.begin notification' => [
            self::headers('notification', 'channel.ad_break.begin', '1'),
            self::body('channel-ad-break-begin-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelAdBreakBeginEvent::class, $result->event);

                self::assertSame('channel.ad_break.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.ban notification' => [
            self::headers('notification', 'channel.ban', '1'),
            self::body('channel-ban-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelBanEvent::class, $result->event);

                self::assertSame('channel.ban', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'channel.bits.use notification' => [
//            self::headers('notification', 'channel.bits.use', '1'),
//            self::body('channel-bits-use-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelBitsUseEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.channel_points_automatic_reward_redemption.add notification' => [
//            self::headers('notification', 'channel.channel_points_automatic_reward_redemption.add', '1'),
//            self::body('channel-channel-points-automatic-reward-redemption-add-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelPointsAutomaticRewardRedemptionAddEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.channel_points_automatic_reward_redemption.add notification #2' => [
//            self::headers('notification', 'channel.channel_points_automatic_reward_redemption.add', '2'),
//            self::body('channel-channel-points-automatic-reward-redemption-add-v2.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelPointsAutomaticRewardRedemptionAddEvent::class, $result->event);
//            },
//        ];

        yield 'channel.channel_points_custom_reward.add notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward.add', '1'),
            self::body('channel-channel-points-custom-reward-add-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardAddEvent::class, $result->event);

                self::assertSame('channel.channel_points_custom_reward.add', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward_redemption.add notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward_redemption.add', '1'),
            self::body('channel-channel-points-custom-reward-redemption-add-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardRedemptionAddEvent::class, $result->event);

                self::assertSame('channel.channel_points_custom_reward_redemption.add', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward_redemption.update notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward_redemption.update', '1'),
            self::body('channel-channel-points-custom-reward-redemption-update-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardRedemptionUpdateEvent::class, $result->event);

                self::assertSame('channel.channel_points_custom_reward_redemption.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward.remove notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward.remove', '1'),
            self::body('channel-channel-points-custom-reward-remove-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardRemoveEvent::class, $result->event);

                self::assertSame('channel.channel_points_custom_reward.remove', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.channel_points_custom_reward.update notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward.update', '1'),
            self::body('channel-channel-points-custom-reward-update-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPointsCustomRewardUpdateEvent::class, $result->event);

                self::assertSame('channel.channel_points_custom_reward.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.donate notification' => [
            self::headers('notification', 'channel.charity_campaign.donate', '1'),
            self::body('channel-charity-campaign-donate-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignDonateEvent::class, $result->event);

                self::assertSame('channel.charity_campaign.donate', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.progress notification' => [
            self::headers('notification', 'channel.charity_campaign.progress', '1'),
            self::body('channel-charity-campaign-progress-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignProgressEvent::class, $result->event);

                self::assertSame('channel.charity_campaign.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.start notification' => [
            self::headers('notification', 'channel.charity_campaign.start', '1'),
            self::body('channel-charity-campaign-start-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignStartEvent::class, $result->event);

                self::assertSame('channel.charity_campaign.start', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.charity_campaign.stop notification' => [
            self::headers('notification', 'channel.charity_campaign.stop', '1'),
            self::body('channel-charity-campaign-stop-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(CharityCampaignStopEvent::class, $result->event);

                self::assertSame('channel.charity_campaign.stop', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat.clear_user_messages notification' => [
            self::headers('notification', 'channel.chat.clear_user_messages', '1'),
            self::body('channel-chat-clear-user-messages-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatClearUserMessagesEvent::class, $result->event);

                self::assertSame('channel.chat.clear_user_messages', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat.clear notification' => [
            self::headers('notification', 'channel.chat.clear', '1'),
            self::body('channel-chat-clear-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatClearEvent::class, $result->event);

                self::assertSame('channel.chat.clear', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat.message_delete notification' => [
            self::headers('notification', 'channel.chat.message_delete', '1'),
            self::body('channel-chat-message-delete-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatMessageDeleteEvent::class, $result->event);

                self::assertSame('channel.chat.message_delete', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat.message notification' => [
            self::headers('notification', 'channel.chat.message', '1'),
            self::body('channel-chat-message-v1-2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatMessageEvent::class, $result->event);

                self::assertSame('channel.chat.message', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat.message notification #2' => [
            self::headers('notification', 'channel.chat.message', '1'),
            self::body('channel-chat-message-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatMessageEvent::class, $result->event);

                self::assertSame('channel.chat.message', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat.notification notification' => [
            self::headers('notification', 'channel.chat.notification', '1'),
            self::body('channel-chat-notification-v1-2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatNotificationEvent::class, $result->event);

                self::assertSame('channel.chat.notification', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat.notification notification #2' => [
            self::headers('notification', 'channel.chat.notification', '1'),
            self::body('channel-chat-notification-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatNotificationEvent::class, $result->event);

                self::assertSame('channel.chat.notification', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.chat_settings.update notification' => [
            self::headers('notification', 'channel.chat_settings.update', '1'),
            self::body('channel-chat-settings-update-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelChatSettingsUpdateEvent::class, $result->event);

                self::assertSame('channel.chat_settings.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'channel.chat.user_message_hold notification' => [
//            self::headers('notification', 'channel.chat.user_message_hold', '1'),
//            self::body('channel-chat-user-message-hold-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelChatClearUserMessageHoldEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.chat.user_message_update notification' => [
//            self::headers('notification', 'channel.chat.user_message_update', '1'),
//            self::body('channel-chat-user-message-update-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelChatUserMessageUpdateEvent::class, $result->event);
//            },
//        ];

        yield 'channel.cheer notification' => [
            self::headers('notification', 'channel.cheer', '1'),
            self::body('channel-cheer-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelCheerEvent::class, $result->event);

                self::assertSame('channel.cheer', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'channel.custom_power_up_redemption.add notification' => [
//            self::headers('notification', 'channel.custom_power_up_redemption.add', '1'),
//            self::body('channel-custom-power-up-redemption-add-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelCustomPowerUpRedemptionAddEvent::class, $result->event);
//            },
//        ];

        yield 'channel.follow notification' => [
            self::headers('notification', 'channel.follow', '2'),
            self::body('channel-follow-v2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelFollowEvent::class, $result->event);
                self::assertSame('cool_user', $result->event->userLogin);

                self::assertSame('channel.follow', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.goal.begin notification' => [
            self::headers('notification', 'channel.goal.begin', '1'),
            self::body('channel-goal-begin-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(GoalBeginEvent::class, $result->event);

                self::assertSame('channel.goal.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.goal.end notification' => [
            self::headers('notification', 'channel.goal.end', '1'),
            self::body('channel-goal-end-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(GoalEndEvent::class, $result->event);

                self::assertSame('channel.goal.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.goal.progress notification' => [
            self::headers('notification', 'channel.goal.progress', '1'),
            self::body('channel-goal-progress-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(GoalProgressEvent::class, $result->event);

                self::assertSame('channel.goal.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.guest_star_guest.update notification' => [
            self::headers('notification', 'channel.guest_star_guest.update', 'beta'),
            self::body('channel-guest-star-guest-update-vbeta.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelGuestStarGuestUpdateEvent::class, $result->event);

                self::assertSame('channel.guest_star_guest.update', $result->metadata()->subscriptionType);
                self::assertSame('beta', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.guest_star_session.begin notification' => [
            self::headers('notification', 'channel.guest_star_session.begin', 'beta'),
            self::body('channel-guest-star-session-begin-vbeta.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelGuestStarSessionBeginEvent::class, $result->event);

                self::assertSame('channel.guest_star_session.begin', $result->metadata()->subscriptionType);
                self::assertSame('beta', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.guest_star_session.end notification' => [
            self::headers('notification', 'channel.guest_star_session.end', 'beta'),
            self::body('channel-guest-star-session-end-vbeta.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelGuestStarSessionEndEvent::class, $result->event);

                self::assertSame('channel.guest_star_session.end', $result->metadata()->subscriptionType);
                self::assertSame('beta', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.guest_star_settings.update notification' => [
            self::headers('notification', 'channel.guest_star_settings.update', 'beta'),
            self::body('channel-guest-star-settings-update-vbeta.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelGuestStarSettingsUpdateEvent::class, $result->event);

                self::assertSame('channel.guest_star_settings.update', $result->metadata()->subscriptionType);
                self::assertSame('beta', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.hype_train.begin notification' => [
            self::headers('notification', 'channel.hype_train.begin', '2'),
            self::body('channel-hype-train-begin-v2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(HypeTrainBeginEvent::class, $result->event);

                self::assertSame('channel.hype_train.begin', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.hype_train.end notification' => [
            self::headers('notification', 'channel.hype_train.end', '2'),
            self::body('channel-hype-train-end-v2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(HypeTrainEndEvent::class, $result->event);

                self::assertSame('channel.hype_train.end', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.hype_train.progress notification' => [
            self::headers('notification', 'channel.hype_train.progress', '2'),
            self::body('channel-hype-train-progress-v2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(HypeTrainProgressEvent::class, $result->event);

                self::assertSame('channel.hype_train.progress', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'channel.moderate notification' => [
//            self::headers('notification', 'channel.moderate', '1'),
//            self::body('channel-moderate-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelModerateV1Event::class, $result->event);
//            },
//        ];

//        yield 'channel.moderate notification #2' => [
//            self::headers('notification', 'channel.moderate', '2'),
//            self::body('channel-moderate-v2-2.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelModerateEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.moderate notification #3' => [
//            self::headers('notification', 'channel.moderate', '2'),
//            self::body('channel-moderate-v2-3.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelModerateEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.moderate notification #4' => [
//            self::headers('notification', 'channel.moderate', '2'),
//            self::body('channel-moderate-v2-4.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelModerateEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.moderate notification #5' => [
//            self::headers('notification', 'channel.moderate', '2'),
//            self::body('channel-moderate-v2.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelModerateEvent::class, $result->event);
//            },
//        ];

        yield 'channel.moderator.add notification' => [
            self::headers('notification', 'channel.moderator.add', '1'),
            self::body('channel-moderator-add-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelModeratorAddEvent::class, $result->event);

                self::assertSame('channel.moderator.add', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.moderator.remove notification' => [
            self::headers('notification', 'channel.moderator.remove', '1'),
            self::body('channel-moderator-remove-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelModeratorRemoveEvent::class, $result->event);

                self::assertSame('channel.moderator.remove', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.poll.begin notification' => [
            self::headers('notification', 'channel.poll.begin', '1'),
            self::body('channel-poll-begin-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPollBeginEvent::class, $result->event);

                self::assertSame('channel.poll.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.poll.end notification' => [
            self::headers('notification', 'channel.poll.end', '1'),
            self::body('channel-poll-end-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPollEndEvent::class, $result->event);

                self::assertSame('channel.poll.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.poll.progress notification' => [
            self::headers('notification', 'channel.poll.progress', '1'),
            self::body('channel-poll-progress-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPollProgressEvent::class, $result->event);

                self::assertSame('channel.poll.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.begin notification' => [
            self::headers('notification', 'channel.prediction.begin', '1'),
            self::body('channel-prediction-begin-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionBeginEvent::class, $result->event);

                self::assertSame('channel.prediction.begin', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.end notification' => [
            self::headers('notification', 'channel.prediction.end', '1'),
            self::body('channel-prediction-end-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionEndEvent::class, $result->event);

                self::assertSame('channel.prediction.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.lock notification' => [
            self::headers('notification', 'channel.prediction.lock', '1'),
            self::body('channel-prediction-lock-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionLockEvent::class, $result->event);

                self::assertSame('channel.prediction.lock', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.prediction.progress notification' => [
            self::headers('notification', 'channel.prediction.progress', '1'),
            self::body('channel-prediction-progress-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelPredictionProgressEvent::class, $result->event);

                self::assertSame('channel.prediction.progress', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.raid notification' => [
            self::headers('notification', 'channel.raid', '1'),
            self::body('channel-raid-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelRaidEvent::class, $result->event);

                self::assertSame('channel.raid', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'channel.shared_chat.begin notification' => [
//            self::headers('notification', 'channel.shared_chat.begin', '1'),
//            self::body('channel-shared-chat-begin-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelSharedChatBeginEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.shared_chat.end notification' => [
//            self::headers('notification', 'channel.shared_chat.end', '1'),
//            self::body('channel-shared-chat-end-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelSharedChatEndEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.shared_chat.update notification' => [
//            self::headers('notification', 'channel.shared_chat.update', '1'),
//            self::body('channel-shared-chat-update-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelSharedChatUpdateEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.shield_mode.begin notification' => [
//            self::headers('notification', 'channel.shield_mode.begin', '1'),
//            self::body('channel-shield-mode-begin-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelShieldModeBeginEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.shield_mode.end notification' => [
//            self::headers('notification', 'channel.shield_mode.end', '1'),
//            self::body('channel-shield-mode-end-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelShieldModeEndEvent::class, $result->event);
//            },
//        ];

        yield 'channel.shoutout.create notification' => [
            self::headers('notification', 'channel.shoutout.create', '1'),
            self::body('channel-shoutout-create-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ShoutoutCreateEvent::class, $result->event);

                self::assertSame('channel.shoutout.create', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.shoutout.receive notification' => [
            self::headers('notification', 'channel.shoutout.receive', '1'),
            self::body('channel-shoutout-receive-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ShoutoutReceiveEvent::class, $result->event);

                self::assertSame('channel.shoutout.receive', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscribe notification' => [
            self::headers('notification', 'channel.subscribe', '1'),
            self::body('channel-subscribe-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscribeEvent::class, $result->event);

                self::assertSame('channel.subscribe', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscription.end notification' => [
            self::headers('notification', 'channel.subscription.end', '1'),
            self::body('channel-subscription-end-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscriptionEndEvent::class, $result->event);

                self::assertSame('channel.subscription.end', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscription.gift notification' => [
            self::headers('notification', 'channel.subscription.gift', '1'),
            self::body('channel-subscription-gift-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscriptionGiftEvent::class, $result->event);

                self::assertSame('channel.subscription.gift', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.subscription.message notification' => [
            self::headers('notification', 'channel.subscription.message', '1'),
            self::body('channel-subscription-message-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelSubscriptionMessageEvent::class, $result->event);

                self::assertSame('channel.subscription.message', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'channel.suspicious_user.message notification' => [
//            self::headers('notification', 'channel.suspicious_user.message', '1'),
//            self::body('channel-suspicious-user-message-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelSuspiciousUserMessageEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.suspicious_user.update notification' => [
//            self::headers('notification', 'channel.suspicious_user.update', '1'),
//            self::body('channel-suspicious-user-update-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelSuspiciousUserUpdateEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.unban_request.create notification' => [
//            self::headers('notification', 'channel.unban_request.create', '1'),
//            self::body('channel-unban-request-create-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelUnbanRequestCreateEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.unban_request.resolve notification' => [
//            self::headers('notification', 'channel.unban_request.resolve', '1'),
//            self::body('channel-unban-request-resolve-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelUnbanRequestResolveEvent::class, $result->event);
//            },
//        ];

        yield 'channel.unban notification' => [
            self::headers('notification', 'channel.unban', '1'),
            self::body('channel-unban-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelUnbanEvent::class, $result->event);

                self::assertSame('channel.unban', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'channel.update notification' => [
            self::headers('notification', 'channel.update', '2'),
            self::body('channel-update-v2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelUpdateEvent::class, $result->event);

                self::assertSame('channel.update', $result->metadata()->subscriptionType);
                self::assertSame('2', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'channel.vip.add notification' => [
//            self::headers('notification', 'channel.vip.add', '1'),
//            self::body('channel-vip-add-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelVipAddEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.vip.remove notification' => [
//            self::headers('notification', 'channel.vip.remove', '1'),
//            self::body('channel-vip-remove-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelVipRemoveEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.warning.acknowledge notification' => [
//            self::headers('notification', 'channel.warning.acknowledge', '1'),
//            self::body('channel-warning-acknowledge-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelWarningAckowledgeEvent::class, $result->event);
//            },
//        ];

//        yield 'channel.warning.send notification' => [
//            self::headers('notification', 'channel.warning.send', '1'),
//            self::body('channel-warning-send-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ChannelWarningSendEvent::class, $result->event);
//            },
//        ];

//        yield 'conduit.shard.disabled notification' => [
//            self::headers('notification', 'conduit.shard.disabled', '1'),
//            self::body('conduit-shard-disabled-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(ConduitShardDisabledEvent::class, $result->event);
//            },
//        ];

        yield 'drop.entitlement.grant notification' => [
            self::headers('notification', 'drop.entitlement.grant', '1'),
            self::body('drop-entitlement-grant-v1-batch.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(DropEntitlementGrantEvent::class, $result->event);

                self::assertSame('drop.entitlement.grant', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'extension.bits_transaction.create notification' => [
            self::headers('notification', 'extension.bits_transaction.create', '1'),
            self::body('extension-bits-transaction-create-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ExtensionBitsTransactionCreateEvent::class, $result->event);

                self::assertSame('extension.bits_transaction.create', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'stream.offline notification' => [
            self::headers('notification', 'stream.offline', '1'),
            self::body('stream-offline-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(StreamOfflineEvent::class, $result->event);

                self::assertSame('stream.offline', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'stream.online notification' => [
            self::headers('notification', 'stream.online', '1'),
            self::body('stream-online-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(StreamOnlineEvent::class, $result->event);

                self::assertSame('stream.online', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'user.authorization.grant notification' => [
            self::headers('notification', 'user.authorization.grant', '1'),
            self::body('user-authorization-grant-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(UserAuthorizationGrantEvent::class, $result->event);

                self::assertSame('user.authorization.grant', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'user.authorization.revoke notification' => [
            self::headers('notification', 'user.authorization.revoke', '1'),
            self::body('user-authorization-revoke-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(UserAuthorizationRevokeEvent::class, $result->event);

                self::assertSame('user.authorization.revoke', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

        yield 'user.update notification' => [
            self::headers('notification', 'user.update', '1'),
            self::body('user-update-v1.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(UserUpdateEvent::class, $result->event);

                self::assertSame('user.update', $result->metadata()->subscriptionType);
                self::assertSame('1', $result->metadata()->subscriptionVersion);
            },
        ];

//        yield 'user.whisper.message notification' => [
//            self::headers('notification', 'user.whisper.message', '1'),
//            self::body('user-whisper-message-v1.json'),
//            function (mixed $result): void {
//                self::assertInstanceOf(EventSubNotification::class, $result);
//                self::assertInstanceOf(UserWhisperMessageEvent::class, $result->event);
//            },
//        ];
    }

    /**
     * @return array<string, string>
     */
    private static function headers(string $messageType, string $subscriptionType, string $version): array
    {
        return [
            'Twitch-Eventsub-Message-Id' => 'id-' . $subscriptionType . '-' . $messageType,
            'Twitch-Eventsub-Message-Retry' => '0',
            'Twitch-Eventsub-Message-Type' => $messageType,
            'Twitch-Eventsub-Message-Timestamp' => '2024-01-01T12:00:00Z',
            'Twitch-Eventsub-Subscription-Type' => $subscriptionType,
            'Twitch-Eventsub-Subscription-Version' => $version,
        ];
    }

    private static function body(string $file): string
    {
        return file_get_contents(__DIR__ . '/Fixtures/' . $file);
    }

    private function sign(string $messageId, string $timestamp, string $body): string
    {
        return 'sha256=' . hash_hmac('sha256', $messageId . $timestamp . $body, self::SECRET);
    }
}
