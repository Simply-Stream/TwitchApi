<?php

namespace SimplyStream\TwitchApi\Tests\Functional\EventSub;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\EventSub\Clock\ClockInterface;
use SimplyStream\TwitchApi\EventSub\Dedup\InMemoryProcessedMessageStore;
use SimplyStream\TwitchApi\EventSub\Events\ChannelFollowEvent;
use SimplyStream\TwitchApi\EventSub\EventSubMessageProcessor;
use SimplyStream\TwitchApi\EventSub\Http\EventSubHeaders;
use SimplyStream\TwitchApi\EventSub\Http\RawEventSubMessage;
use SimplyStream\TwitchApi\EventSub\Messages\EventSubNotification;
use SimplyStream\TwitchApi\EventSub\Registry\EventSubTypeRegistryBuilder;
use SimplyStream\TwitchApi\EventSub\Security\MessageFreshnessValidator;
use SimplyStream\TwitchApi\EventSub\Security\MessageSignatureVerifier;
use SimplyStream\TwitchApi\EventSub\Serialization\DenormalizerInterface;
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
                // TODO
            },
        ];
        yield 'automod.message.hold notification #2' => [
            self::headers('notification', 'automod.message.hold', '2'),
            self::body('automod-message-hold-v2-2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'automod.message.hold notification #3' => [
            self::headers('notification', 'automod.message.hold', '2'),
            self::body('automod-message-hold-v2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'automod.message.update notification' => [
            self::headers('notification', 'automod.message.update', '1'),
            self::body('automod-message-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'automod.settings.update notification' => [
            self::headers('notification', 'automod.settings.update', '1'),
            self::body('automod-settings-update-v1-nested-data.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'automod.terms.update notification' => [
            self::headers('notification', 'automod.terms.update', '1'),
            self::body('automod-terms-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.ad_break.begin notification' => [
            self::headers('notification', 'channel.ad_break.begin', '1'),
            self::body('channel-ad-break-begin-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.ban notification' => [
            self::headers('notification', 'channel.ban', '1'),
            self::body('channel-ban-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.bits.use notification' => [
            self::headers('notification', 'channel.bits.use', '1'),
            self::body('channel-bits-use-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.channel_points_automatic_reward_redemption.add notification' => [
            self::headers('notification', 'channel.channel_points_automatic_reward_redemption.add', '1'),
            self::body('channel-channel-points-automatic-reward-redemption-add-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.channel_points_automatic_reward_redemption.add notification #2' => [
            self::headers('notification', 'channel.channel_points_automatic_reward_redemption.add', '2'),
            self::body('channel-channel-points-automatic-reward-redemption-add-v2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.channel_points_custom_reward.add notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward.add', '1'),
            self::body('channel-channel-points-custom-reward-add-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.channel_points_custom_reward_redemption.add notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward_redemption.add', '1'),
            self::body('channel-channel-points-custom-reward-redemption-add-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.channel_points_custom_reward_redemption.update notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward_redemption.update', '1'),
            self::body('channel-channel-points-custom-reward-redemption-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.channel_points_custom_reward.remove notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward.remove', '1'),
            self::body('channel-channel-points-custom-reward-remove-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.channel_points_custom_reward.update notification' => [
            self::headers('notification', 'channel.channel_points_custom_reward.update', '1'),
            self::body('channel-channel-points-custom-reward-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.charity_campaign.donate notification' => [
            self::headers('notification', 'channel.charity_campaign.donate', '1'),
            self::body('channel-charity-campaign-donate-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.charity_campaign.progress notification' => [
            self::headers('notification', 'channel.charity_campaign.progress', '1'),
            self::body('channel-charity-campaign-progress-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.charity_campaign.start notification' => [
            self::headers('notification', 'channel.charity_campaign.start', '1'),
            self::body('channel-charity-campaign-start-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.charity_campaign.stop notification' => [
            self::headers('notification', 'channel.charity_campaign.stop', '1'),
            self::body('channel-charity-campaign-stop-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.clear_user_messages notification' => [
            self::headers('notification', 'channel.chat.clear_user_messages', '1'),
            self::body('channel-chat-clear-user-messages-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.clear notification' => [
            self::headers('notification', 'channel.chat.clear', '1'),
            self::body('channel-chat-clear-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.message_delete notification' => [
            self::headers('notification', 'channel.chat.message_delete', '1'),
            self::body('channel-chat-message-delete-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.message notification' => [
            self::headers('notification', 'channel.chat.message', '1'),
            self::body('channel-chat-message-v1-2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.message notification #2' => [
            self::headers('notification', 'channel.chat.message', '1'),
            self::body('channel-chat-message-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.notification notification' => [
            self::headers('notification', 'channel.chat.notification', '1'),
            self::body('channel-chat-notification-v1-2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.notification notification #2' => [
            self::headers('notification', 'channel.chat.notification', '1'),
            self::body('channel-chat-notification-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat_settings.update notification' => [
            self::headers('notification', 'channel.chat_settings.update', '1'),
            self::body('channel-chat-settings-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.user_message_hold notification' => [
            self::headers('notification', 'channel.chat.user_message_hold', '1'),
            self::body('channel-chat-user-message-hold-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.chat.user_message_update notification' => [
            self::headers('notification', 'channel.chat.user_message_update', '1'),
            self::body('channel-chat-user-message-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.cheer notification' => [
            self::headers('notification', 'channel.cheer', '1'),
            self::body('channel-cheer-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.custom_power_up_redemption.add notification' => [
            self::headers('notification', 'channel.custom_power_up_redemption.add', '1'),
            self::body('channel-custom-power-up-redemption-add-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.follow notification' => [
            self::headers('notification', 'channel.follow', '2'),
            self::body('channel-follow-v2.json'),
            function (mixed $result): void {
                self::assertInstanceOf(EventSubNotification::class, $result);
                self::assertInstanceOf(ChannelFollowEvent::class, $result->event);
                self::assertSame('cool_user', $result->event->userLogin);
            },
        ];
        yield 'channel.goal.begin notification' => [
            self::headers('notification', 'channel.goal.begin', '1'),
            self::body('channel-goal-begin-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.goal.end notification' => [
            self::headers('notification', 'channel.goal.end', '1'),
            self::body('channel-goal-end-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.goal.progress notification' => [
            self::headers('notification', 'channel.goal.progress', '1'),
            self::body('channel-goal-progress-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.guest_star_guest.update notification' => [
            self::headers('notification', 'channel.guest_star_guest.update', 'beta'),
            self::body('channel-guest-star-guest-update-vbeta.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.guest_star_session.begin notification' => [
            self::headers('notification', 'channel.guest_star_session.begin', 'beta'),
            self::body('channel-guest-star-session-begin-vbeta.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.guest_star_session.end notification' => [
            self::headers('notification', 'channel.guest_star_session.end', 'beta'),
            self::body('channel-guest-star-session-end-vbeta.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.guest_star_settings.update notification' => [
            self::headers('notification', 'channel.guest_star_settings.update', 'beta'),
            self::body('channel-guest-star-settings-update-vbeta.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.hype_train.begin notification' => [
            self::headers('notification', 'channel.hype_train.begin', '2'),
            self::body('channel-hype-train-begin-v2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.hype_train.end notification' => [
            self::headers('notification', 'channel.hype_train.end', '2'),
            self::body('channel-hype-train-end-v2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.hype_train.progress notification' => [
            self::headers('notification', 'channel.hype_train.progress', '2'),
            self::body('channel-hype-train-progress-v2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.moderate notification' => [
            self::headers('notification', 'channel.moderate', '1'),
            self::body('channel-moderate-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.moderate notification #2' => [
            self::headers('notification', 'channel.moderate', '2'),
            self::body('channel-moderate-v2-2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.moderate notification #3' => [
            self::headers('notification', 'channel.moderate', '2'),
            self::body('channel-moderate-v2-3.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.moderate notification #4' => [
            self::headers('notification', 'channel.moderate', '2'),
            self::body('channel-moderate-v2-4.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.moderate notification #5' => [
            self::headers('notification', 'channel.moderate', '2'),
            self::body('channel-moderate-v2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.moderator.add notification' => [
            self::headers('notification', 'channel.moderator.add', '1'),
            self::body('channel-moderator-add-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.moderator.remove notification' => [
            self::headers('notification', 'channel.moderator.remove', '1'),
            self::body('channel-moderator-remove-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.poll.begin notification' => [
            self::headers('notification', 'channel.poll.begin', '1'),
            self::body('channel-poll-begin-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.poll.end notification' => [
            self::headers('notification', 'channel.poll.end', '1'),
            self::body('channel-poll-end-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.poll.progress notification' => [
            self::headers('notification', 'channel.poll.progress', '1'),
            self::body('channel-poll-progress-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.prediction.begin notification' => [
            self::headers('notification', 'channel.prediction.begin', '1'),
            self::body('channel-prediction-begin-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.prediction.end notification' => [
            self::headers('notification', 'channel.prediction.end', '1'),
            self::body('channel-prediction-end-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.prediction.lock notification' => [
            self::headers('notification', 'channel.prediction.lock', '1'),
            self::body('channel-prediction-lock-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.prediction.progress notification' => [
            self::headers('notification', 'channel.prediction.progress', '1'),
            self::body('channel-prediction-progress-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.raid notification' => [
            self::headers('notification', 'channel.raid', '1'),
            self::body('channel-raid-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.shared_chat.begin notification' => [
            self::headers('notification', 'channel.shared_chat.begin', '1'),
            self::body('channel-shared-chat-begin-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.shared_chat.end notification' => [
            self::headers('notification', 'channel.shared_chat.end', '1'),
            self::body('channel-shared-chat-end-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.shared_chat.update notification' => [
            self::headers('notification', 'channel.shared_chat.update', '1'),
            self::body('channel-shared-chat-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.shield_mode.begin notification' => [
            self::headers('notification', 'channel.shield_mode.begin', '1'),
            self::body('channel-shield-mode-begin-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.shield_mode.end notification' => [
            self::headers('notification', 'channel.shield_mode.end', '1'),
            self::body('channel-shield-mode-end-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.shoutout.create notification' => [
            self::headers('notification', 'channel.shoutout.create', '1'),
            self::body('channel-shoutout-create-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.shoutout.receive notification' => [
            self::headers('notification', 'channel.shoutout.receive', '1'),
            self::body('channel-shoutout-receive-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.subscribe notification' => [
            self::headers('notification', 'channel.subscribe', '1'),
            self::body('channel-subscribe-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.subscription.end notification' => [
            self::headers('notification', 'channel.subscription.end', '1'),
            self::body('channel-subscription-end-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.subscription.gift notification' => [
            self::headers('notification', 'channel.subscription.gift', '1'),
            self::body('channel-subscription-gift-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.subscription.message notification' => [
            self::headers('notification', 'channel.subscription.message', '1'),
            self::body('channel-subscription-message-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.suspicious_user.message notification' => [
            self::headers('notification', 'channel.suspicious_user.message', '1'),
            self::body('channel-suspicious-user-message-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.suspicious_user.update notification' => [
            self::headers('notification', 'channel.suspicious_user.update', '1'),
            self::body('channel-suspicious-user-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.unban_request.create notification' => [
            self::headers('notification', 'channel.unban_request.create', '1'),
            self::body('channel-unban-request-create-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.unban_request.resolve notification' => [
            self::headers('notification', 'channel.unban_request.resolve', '1'),
            self::body('channel-unban-request-resolve-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.unban notification' => [
            self::headers('notification', 'channel.unban', '1'),
            self::body('channel-unban-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.update notification' => [
            self::headers('notification', 'channel.update', '2'),
            self::body('channel-update-v2.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.vip.add notification' => [
            self::headers('notification', 'channel.vip.add', '1'),
            self::body('channel-vip-add-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.vip.remove notification' => [
            self::headers('notification', 'channel.vip.remove', '1'),
            self::body('channel-vip-remove-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.warning.acknowledge notification' => [
            self::headers('notification', 'channel.warning.acknowledge', '1'),
            self::body('channel-warning-acknowledge-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'channel.warning.send notification' => [
            self::headers('notification', 'channel.warning.send', '1'),
            self::body('channel-warning-send-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'conduit.shard.disabled notification' => [
            self::headers('notification', 'conduit.shard.disabled', '1'),
            self::body('conduit-shard-disabled-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'drop.entitlement.grant notification' => [
            self::headers('notification', 'drop.entitlement.grant', '1'),
            self::body('drop-entitlement-grant-v1-batch.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'extension.bits_transaction.create notification' => [
            self::headers('notification', 'extension.bits_transaction.create', '1'),
            self::body('extension-bits-transaction-create-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'stream.offline notification' => [
            self::headers('notification', 'stream.offline', '1'),
            self::body('stream-offline-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'stream.online notification' => [
            self::headers('notification', 'stream.online', '1'),
            self::body('stream-online-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'user.authorization.grant notification' => [
            self::headers('notification', 'user.authorization.grant', '1'),
            self::body('user-authorization-grant-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'user.authorization.revoke notification' => [
            self::headers('notification', 'user.authorization.revoke', '1'),
            self::body('user-authorization-revoke-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'user.update notification' => [
            self::headers('notification', 'user.update', '1'),
            self::body('user-update-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
        yield 'user.whisper.message notification' => [
            self::headers('notification', 'user.whisper.message', '1'),
            self::body('user-whisper-message-v1.json'),
            function (mixed $result): void {
                // TODO
            },
        ];
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
