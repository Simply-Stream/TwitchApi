<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Exception\InvalidSource;
use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\EventSubApi;
use SimplyStream\TwitchApi\Helix\EventSub\EventSubService;
use SimplyStream\TwitchApi\Helix\EventSub\Exceptions\ChallengeMissingException;
use SimplyStream\TwitchApi\Helix\EventSub\Exceptions\InvalidSignatureException;
use SimplyStream\TwitchApi\Helix\EventSub\Exceptions\UnsupportedEventException;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelBanCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Condition\ChannelPointsCustomRewardAddCondition;
use SimplyStream\TwitchApi\Helix\Models\EventSub\EventResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Events;
use SimplyStream\TwitchApi\Helix\Models\EventSub\MultipleEventResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscriptions;

#[CoversClass(EventSubService::class)]
class EventSubServiceTest extends TestCase
{
    /**
     * @return array[]
     */
    public static function handleSubscriptionCallbackSuccessDataProvider(): array
    {
        return [
            'channel.ban event' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "b31a158e-35fc-a957-ab56-17b49c0a3f70",
        "status": "enabled",
        "type": "channel.ban",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "68771962"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-23T02:55:36.947776627Z",
        "cost": 0
    },
    "event": {
        "user_id": "63246091",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "68771962",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "moderator_user_id": "6572586",
        "moderator_user_login": "CLIModerator",
        "moderator_user_name": "CLIModerator",
        "reason": "This is a test event",
        "banned_at": "2023-11-23T02:55:36.947776627Z",
        "ends_at": null,
        "is_permanent": true
    }
}
JSON,
                'type' => 'channel.ban',
                'signature' => 'sha256=db8eb70ae42f34b14268ba9454a682e7d02f12efd725b4168edd25e4a7c499a5',
                'assertions' => function (EventResponse $eventResponse) {
                    /** @var Events\ChannelBanEvent $event */
                    $event = $eventResponse->getEvent();
                    $subscription = $eventResponse->getSubscription();
                    self::assertInstanceOf(Events\ChannelBanEvent::class, $event);
                    self::assertInstanceOf(Subscriptions\ChannelBanSubscription::class, $subscription);

                    self::assertSame("enabled", $subscription->getStatus());
                    self::assertSame("channel.ban", $subscription->getType());
                    self::assertInstanceOf(ChannelBanCondition::class, $subscription->getCondition());
                    self::assertSame("68771962", $subscription->getCondition()->getBroadcasterUserId());

                    self::assertSame("63246091", $event->getUserId());
                    self::assertSame("testFromUser", $event->getUserLogin());
                    self::assertSame("testFromUser", $event->getUserName());
                    self::assertSame("68771962", $event->getBroadcasterUserId());
                    self::assertSame("testBroadcaster", $event->getBroadcasterUserLogin());
                    self::assertSame("testBroadcaster", $event->getBroadcasterUserName());
                    self::assertSame("6572586", $event->getModeratorUserId());
                    self::assertSame("CLIModerator", $event->getModeratorUserLogin());
                    self::assertSame("CLIModerator", $event->getModeratorUserName());
                    self::assertSame("This is a test event", $event->getReason());

                    self::assertInstanceOf(\DateTimeImmutable::class, $event->getBannedAt());
                    self::assertEquals(new \DateTimeImmutable("2023-11-23T02:55:36.947776627Z"), $event->getBannedAt());
                    self::assertNull($event->getEndsAt());
                    self::assertTrue($event->isPermanent());
                },
            ],
            'channel.channel_points_custom_reward.add' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "b6f490b7-8a14-6b23-933d-55470a973f8a",
        "status": "enabled",
        "type": "channel.channel_points_custom_reward.add",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "70259460"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-24T02:22:32.508924026Z",
        "cost": 0
    },
    "event": {
        "id": "b6f490b7-8a14-6b23-933d-55470a973f8a",
        "broadcaster_user_id": "70259460",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "is_enabled": true,
        "is_paused": false,
        "is_in_stock": true,
        "title": "Test Reward from CLI",
        "cost": 150,
        "prompt": "Redeem Your Test Reward from CLI",
        "is_user_input_required": true,
        "should_redemptions_skip_request_queue": false,
        "cooldown_expires_at": "2023-11-24T02:22:32.508924026Z",
        "redemptions_redeemed_current_stream": 0,
        "max_per_stream": {
            "is_enabled": true,
            "value": 100
        },
        "max_per_user_per_stream": {
            "is_enabled": true,
            "value": 100
        },
        "global_cooldown": {
            "is_enabled": true,
            "seconds": 300
        },
        "background_color": "#c0ffee",
        "image": {
            "url_1x": "https://static-cdn.jtvnw.net/image-1.png",
            "url_2x": "https://static-cdn.jtvnw.net/image-2.png",
            "url_4x": "https://static-cdn.jtvnw.net/image-4.png"
        },
        "default_image": {
            "url_1x": "https://static-cdn.jtvnw.net/default-1.png",
            "url_2x": "https://static-cdn.jtvnw.net/default-2.png",
            "url_4x": "https://static-cdn.jtvnw.net/default-4.png"
        }
    }
}
JSON,
                'type' => 'channel.channel_points_custom_reward.add',
                'signature' => 'sha256=c6394a12629ee6152b57830433f9f7f595c3264e04e0d549a46c2de77dd05b6a',
                'assertions' => function (EventResponse $eventResponse) {
                    $subscription = $eventResponse->getSubscription();
                    $event = $eventResponse->getEvent();

                    self::assertInstanceOf(Events\ChannelPointsCustomRewardAddEvent::class, $event);
                    self::assertInstanceOf(
                        Subscriptions\ChannelPointsCustomRewardAddSubscription::class,
                        $subscription
                    );

                    self::assertSame("enabled", $subscription->getStatus());
                    self::assertSame("channel.channel_points_custom_reward.add", $subscription->getType());
                    self::assertInstanceOf(ChannelPointsCustomRewardAddCondition::class, $subscription->getCondition());
                    self::assertSame("70259460", $subscription->getCondition()->getBroadcasterUserId());

                    self::assertSame("70259460", $event->getBroadcasterUserId());
                    self::assertSame("testBroadcaster", $event->getBroadcasterUserLogin());
                    self::assertSame("testBroadcaster", $event->getBroadcasterUserName());
                    self::assertTrue($event->isEnabled());
                    self::assertFalse($event->isPaused());
                    self::assertTrue($event->isInStock());
                    self::assertSame("Test Reward from CLI", $event->getTitle());
                    self::assertSame(150, $event->getCost());
                    self::assertSame("Redeem Your Test Reward from CLI", $event->getPrompt());
                    self::assertTrue($event->isUserInputRequired());
                    self::assertFalse($event->isShouldRedemptionsSkipRequestQueue());
                    self::assertEquals(
                        new \DateTimeImmutable("2023-11-24T02:22:32.508924026Z"),
                        $event->getCooldownExpiresAt()
                    );
                    self::assertSame(0, $event->getRedemptionsRedeemedCurrentStream());
                },
            ],
            'channel.channel_points_custom_reward.remove' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "43e199b5-b606-dd89-d8c7-052112655366",
        "status": "enabled",
        "type": "channel.channel_points_custom_reward.remove",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "75094983"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-24T02:36:43.778936316Z",
        "cost": 0
    },
    "event": {
        "id": "43e199b5-b606-dd89-d8c7-052112655366",
        "broadcaster_user_id": "75094983",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "is_enabled": true,
        "is_paused": false,
        "is_in_stock": true,
        "title": "Test Reward from CLI",
        "cost": 150,
        "prompt": "Redeem Your Test Reward from CLI",
        "is_user_input_required": true,
        "should_redemptions_skip_request_queue": false,
        "cooldown_expires_at": "2023-11-24T02:36:43.778936316Z",
        "redemptions_redeemed_current_stream": 0,
        "max_per_stream": {
            "is_enabled": true,
            "value": 100
        },
        "max_per_user_per_stream": {
            "is_enabled": true,
            "value": 100
        },
        "global_cooldown": {
            "is_enabled": true,
            "seconds": 300
        },
        "background_color": "#c0ffee",
        "image": {
            "url_1x": "https://static-cdn.jtvnw.net/image-1.png",
            "url_2x": "https://static-cdn.jtvnw.net/image-2.png",
            "url_4x": "https://static-cdn.jtvnw.net/image-4.png"
        },
        "default_image": {
            "url_1x": "https://static-cdn.jtvnw.net/default-1.png",
            "url_2x": "https://static-cdn.jtvnw.net/default-2.png",
            "url_4x": "https://static-cdn.jtvnw.net/default-4.png"
        }
    }
}
JSON,
                'type' => 'channel.channel_points_custom_reward.remove',
                'signature' => 'sha256=b542c5796636f79fd3753680519675246df3a7aee4be2206ed0e678c089e1b91',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(
                        Events\ChannelPointsCustomRewardRemoveEvent::class,
                        $eventResponse->getEvent()
                    );
                    self::assertInstanceOf(
                        Subscriptions\ChannelPointsCustomRewardRemoveSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.channel_points_custom_reward.update' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "b06437cd-2038-d3b4-2586-2736f8e789b8",
        "status": "enabled",
        "type": "channel.channel_points_custom_reward.update",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "94746717"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-24T02:40:55.554745237Z",
        "cost": 0
    },
    "event": {
        "id": "b06437cd-2038-d3b4-2586-2736f8e789b8",
        "broadcaster_user_id": "94746717",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "is_enabled": true,
        "is_paused": false,
        "is_in_stock": true,
        "title": "Test Reward from CLI",
        "cost": 150,
        "prompt": "Redeem Your Test Reward from CLI",
        "is_user_input_required": true,
        "should_redemptions_skip_request_queue": false,
        "cooldown_expires_at": "2023-11-24T02:40:55.554745237Z",
        "redemptions_redeemed_current_stream": 0,
        "max_per_stream": {
            "is_enabled": true,
            "value": 100
        },
        "max_per_user_per_stream": {
            "is_enabled": true,
            "value": 100
        },
        "global_cooldown": {
            "is_enabled": true,
            "seconds": 300
        },
        "background_color": "#c0ffee",
        "image": {
            "url_1x": "https://static-cdn.jtvnw.net/image-1.png",
            "url_2x": "https://static-cdn.jtvnw.net/image-2.png",
            "url_4x": "https://static-cdn.jtvnw.net/image-4.png"
        },
        "default_image": {
            "url_1x": "https://static-cdn.jtvnw.net/default-1.png",
            "url_2x": "https://static-cdn.jtvnw.net/default-2.png",
            "url_4x": "https://static-cdn.jtvnw.net/default-4.png"
        }
    }
}
JSON,
                'type' => 'channel.channel_points_custom_reward.update',
                'signature' => 'sha256=b7bb4335f1973652d4f93ff0880560c3ef13f281b71a078381cbea4197593fcb',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(
                        Events\ChannelPointsCustomRewardUpdateEvent::class,
                        $eventResponse->getEvent()
                    );
                    self::assertInstanceOf(
                        Subscriptions\ChannelPointsCustomRewardUpdateSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.channel_points_custom_reward_redemption.add' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "efaa6ff4-65ef-b3b8-1409-873d867b85a6",
        "status": "enabled",
        "type": "channel.channel_points_custom_reward_redemption.add",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "29435005"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-24T16:38:28.808692313Z",
        "cost": 0
    },
    "event": {
        "id": "efaa6ff4-65ef-b3b8-1409-873d867b85a6",
        "broadcaster_user_id": "29435005",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "user_id": "87818135",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "user_input": "Test Input From CLI",
        "status": "unfulfilled",
        "reward": {
            "id": "97b3247f-d95b-0895-bfee-e64d7d5965bf",
            "title": "Test Reward from CLI",
            "cost": 150,
            "prompt": "Redeem Your Test Reward from CLI"
        },
        "redeemed_at": "2023-11-24T16:38:28.808692313Z"
    }
}
JSON,
                'type' => 'channel.channel_points_custom_reward_redemption.add',
                'signature' => 'sha256=2a9569ab5422e5c2d31f2110f0a30ca02d04fba6e696d4cc7ca4fce0d57680a8',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(
                        Events\ChannelPointsCustomRewardRedemptionAddEvent::class,
                        $eventResponse->getEvent()
                    );
                    self::assertInstanceOf(
                        Subscriptions\ChannelPointsCustomRewardRedemptionAddSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.channel_points_custom_reward_redemption.update' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "40c1cb0c-8d5e-28c7-c217-13cea1fe32f9",
        "status": "enabled",
        "type": "channel.channel_points_custom_reward_redemption.update",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "227089"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-24T16:50:42.656852231Z",
        "cost": 0
    },
    "event": {
        "id": "40c1cb0c-8d5e-28c7-c217-13cea1fe32f9",
        "broadcaster_user_id": "227089",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "user_id": "30231133",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "user_input": "Test Input From CLI",
        "status": "unfulfilled",
        "reward": {
            "id": "c9ca8286-cb40-c6bf-8b1a-b7d5f727d6e7",
            "title": "Test Reward from CLI",
            "cost": 150,
            "prompt": "Redeem Your Test Reward from CLI"
        },
        "redeemed_at": "2023-11-24T16:50:42.656852231Z"
    }
}
JSON,
                'type' => 'channel.channel_points_custom_reward_redemption.update',
                'signature' => 'sha256=ed3e7b4014e58acea984247f6a756cd17b5b8ac862311abb6b66f9a084919cde',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(
                        Events\ChannelPointsCustomRewardRedemptionUpdateEvent::class,
                        $eventResponse->getEvent()
                    );
                    self::assertInstanceOf(
                        Subscriptions\ChannelPointsCustomRewardRedemptionUpdateSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.charity_campaign.donate' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "fbe8272d-67b7-b8cb-7a25-3e68baf4acd5",
        "status": "enabled",
        "type": "channel.charity_campaign.donate",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "38426868"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-24T16:53:56.790953496Z",
        "cost": 0
    },
    "event": {
        "campaign_id": "e59080cf-a02c-6bbe-642c-517dda256e2d",
        "id": "59b70dda-cd22-6a77-d00b-55b0a4e580b7",
        "broadcaster_user_id": "38426868",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "user_id": "22314136",
        "user_name": "testFromUser",
        "user_login": "testFromUser",
        "charity_name": "Example Charity",
        "charity_description": "Example Description",
        "charity_logo": "https://abc.cloudfront.net/ppgf/1000/100.png",
        "charity_website": "https://www.example.com",
        "amount": {
            "value": 10000,
            "decimal_places": 2,
            "currency": "USD"
        }
    }
}
JSON,
                'type' => 'channel.charity_campaign.donate',
                'signature' => 'sha256=b5e77cf3dbbc2d538c064828a4ad229f69c32721734202145bdcae13994d06eb',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\CharityDonationEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\CharityDonationSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.charity_campaign.progress' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "793f9a40-bd03-701b-eb9e-792d35eafd41",
        "status": "enabled",
        "type": "channel.charity_campaign.progress",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "61325085"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T01:37:58.69629972Z",
        "cost": 0
    },
    "event": {
        "id": "e5a8e01b-b7a6-01f6-fff7-d578aa1ec6fc",
        "broadcaster_user_id": "61325085",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "charity_name": "Example Charity",
        "charity_description": "Example Description",
        "charity_logo": "https://abc.cloudfront.net/ppgf/1000/100.png",
        "charity_website": "https://www.example.com",
        "current_amount": {
            "value": 260000,
            "decimal_places": 2,
            "currency": "USD"
        },
        "target_amount": {
            "value": 1500000,
            "decimal_places": 2,
            "currency": "USD"
        }
    }
}
JSON,
                'type' => 'channel.charity_campaign.progress',
                'signature' => 'sha256=b8e41b3982b4b85e385f67330c1bcd868d1cb2dff19fc973aaf5e24e160ea550',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\CharityCampaignProgressEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\CharityCampaignProgressSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.charity_campaign.start' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "71d1804c-27e1-9fd9-4546-c71e04937525",
        "status": "enabled",
        "type": "channel.charity_campaign.start",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "73259405"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T01:44:58.391683402Z",
        "cost": 0
    },
    "event": {
        "id": "355cd313-0d2a-6bd2-baf5-aacb559cc65c",
        "broadcaster_user_id": "73259405",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "charity_name": "Example Charity",
        "charity_description": "Example Description",
        "charity_logo": "https://abc.cloudfront.net/ppgf/1000/100.png",
        "charity_website": "https://www.example.com",
        "current_amount": {
            "value": 0,
            "decimal_places": 2,
            "currency": "USD"
        },
        "target_amount": {
            "value": 1500000,
            "decimal_places": 2,
            "currency": "USD"
        },
        "started_at": "2023-11-25T01:44:58.391683402Z"
    }
}
JSON,
                'type' => 'channel.charity_campaign.start',
                'signature' => 'sha256=7789eb61cf8d690546992aaf0001d386e63e553631a6f6c9731756edfa84482b',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\CharityCampaignStartEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\CharityCampaignStartSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.charity_campaign.stop' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "7427fa7f-89a2-9266-0eff-1748cb645cae",
        "status": "enabled",
        "type": "channel.charity_campaign.stop",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "98064159"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T01:51:00.09492985Z",
        "cost": 0
    },
    "event": {
        "id": "9549d9b9-41b4-f95b-9674-fe4caa0a8a84",
        "broadcaster_user_id": "98064159",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "charity_name": "Example Charity",
        "charity_description": "Example Description",
        "charity_logo": "https://abc.cloudfront.net/ppgf/1000/100.png",
        "charity_website": "https://www.example.com",
        "current_amount": {
            "value": 1450000,
            "decimal_places": 2,
            "currency": "USD"
        },
        "target_amount": {
            "value": 1500000,
            "decimal_places": 2,
            "currency": "USD"
        },
        "stopped_at": "2023-11-25T01:51:00.09492985Z"
    }
}
JSON,
                'type' => 'channel.charity_campaign.stop',
                'signature' => 'sha256=e2d2c38b60ce0ac97c7582b97661f88406e48b889e8253a116ca7c08e9ed3df7',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\CharityCampaignStopEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\CharityCampaignStopSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.cheer' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "dceb1a9c-1422-87da-1848-c440793bb0a0",
        "status": "enabled",
        "type": "channel.cheer",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "26326564"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T02:45:04.631683751Z",
        "cost": 0
    },
    "event": {
        "user_id": "37371383",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "26326564",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "is_anonymous": false,
        "message": "This is a test event.",
        "bits": 100
    }
}
JSON,
                'type' => 'channel.cheer',
                'signature' => 'sha256=26e26230dd18960ab27ae377c87d3e6e987e85c84b99b6cfb56574a5b323a540',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelCheerEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelCheerSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.follow' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "56392dc0-52c5-be8e-d117-65be574a0fb5",
        "status": "enabled",
        "type": "channel.follow",
        "version": "2",
        "condition": {
            "broadcaster_user_id": "82131846",
            "moderator_user_id": "89515220"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T03:09:12.437892302Z",
        "cost": 0
    },
    "event": {
        "user_id": "89515220",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "82131846",
        "broadcaster_user_login": "82131846",
        "broadcaster_user_name": "testBroadcaster",
        "followed_at": "2023-11-25T03:09:12.437892302Z"
    }
}
JSON,
                'type' => 'channel.follow',
                'signature' => 'sha256=69bb1aebce3df7b71c38ad99bea7c8dc858545991c755e0c80a506b26600770f',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelFollowEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelFollowSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.goal.begin' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "24a222a9-3c1d-8fbd-8c7e-dfa8c15da1a8",
        "status": "enabled",
        "type": "channel.goal.begin",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "57067417"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T03:32:32.486294774Z",
        "cost": 0
    },
    "event": {
        "id": "24a222a9-3c1d-8fbd-8c7e-dfa8c15da1a8",
        "broadcaster_user_id": "57067417",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "type": "follower",
        "description": "",
        "current_amount": 94,
        "target_amount": 1,
        "started_at": "2023-11-25T03:32:32.486294774Z"
    }
}
JSON,
                'type' => 'channel.goal.begin',
                'signature' => 'sha256=94af83cda03291b02fdcdfcdbef442f32e57c8d281dff41f325bbe9ea2f1399e',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\GoalBeginEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\GoalBeginSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.goal.progress' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "03595663-4643-4b1f-cb77-05b1bf96062c",
        "status": "enabled",
        "type": "channel.goal.progress",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "88022821"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T03:41:09.925291446Z",
        "cost": 0
    },
    "event": {
        "id": "03595663-4643-4b1f-cb77-05b1bf96062c",
        "broadcaster_user_id": "88022821",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "type": "follower",
        "description": "",
        "current_amount": 12,
        "target_amount": 883,
        "started_at": "2023-11-25T03:41:09.925291446Z"
    }
}
JSON,
                'type' => 'channel.goal.progress',
                'signature' => 'sha256=c621d6cbd68da820ff64021c020c265293710d69c81409842ea7301c936896a7',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\GoalProgressEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\GoalProgressSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.goal.end' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "23412c38-2fa4-d468-1e25-ccdda488df16",
        "status": "enabled",
        "type": "channel.goal.end",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "27139120"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T03:40:34.005512832Z",
        "cost": 0
    },
    "event": {
        "id": "23412c38-2fa4-d468-1e25-ccdda488df16",
        "broadcaster_user_id": "27139120",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "type": "follower",
        "description": "",
        "is_achieved": false,
        "current_amount": 74,
        "target_amount": 604,
        "started_at": "2023-11-25T03:40:34.005512832Z",
        "ended_at": "2023-11-26T03:40:34Z"
    }
}
JSON,
                'type' => 'channel.goal.end',
                'signature' => 'sha256=65e8a764da24f1418fce764f7f2eb8da634bd68b8eb2d8a4d034e313e94c4e44',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\GoalEndEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(Subscriptions\GoalEndSubscription::class, $eventResponse->getSubscription());
                },
            ],
            'channel.hype_train.begin' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "63aa1bc2-194f-47a3-8615-7b6f6c99ea71",
        "status": "enabled",
        "type": "channel.hype_train.begin",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "70260482"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T16:27:38.505342045Z",
        "cost": 0
    },
    "event": {
        "id": "63aa1bc2-194f-47a3-8615-7b6f6c99ea71",
        "broadcaster_user_id": "70260482",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "level": 4,
        "total": 321,
        "progress": 321,
        "goal": 22977,
        "top_contributions": [
            {
                "total": 450,
                "type": "other",
                "user_id": "62468558",
                "user_name": "cli_user1",
                "user_login": "cli_user1"
            },
            {
                "total": 359,
                "type": "subscription",
                "user_id": "79164956",
                "user_name": "cli_user2",
                "user_login": "cli_user2"
            }
        ],
        "last_contribution": {
            "total": 359,
            "type": "subscription",
            "user_id": "79164956",
            "user_name": "cli_user2",
            "user_login": "cli_user2"
        },
        "started_at": "2023-11-25T16:27:38.505342045Z",
        "expires_at": "2023-11-25T16:32:38.505342045Z"
    }
}
JSON,
                'type' => 'channel.hype_train.begin',
                'signature' => 'sha256=5f5850b5493d44ea4f3f7f6eedd4e36f64d268956a0438376368a9a5e84ce88d',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\HypeTrainBeginEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\HypeTrainBeginSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.hype_train.progress' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "c8eb08bf-fd85-a82d-83af-235555f38b30",
        "status": "enabled",
        "type": "channel.hype_train.progress",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "51150338"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T16:35:20.479750187Z",
        "cost": 0
    },
    "event": {
        "id": "c8eb08bf-fd85-a82d-83af-235555f38b30",
        "broadcaster_user_id": "51150338",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "level": 1,
        "total": 203,
        "progress": 183,
        "goal": 27000,
        "top_contributions": [
            {
                "total": 931,
                "type": "other",
                "user_id": "95706118",
                "user_name": "cli_user1",
                "user_login": "cli_user1"
            },
            {
                "total": 574,
                "type": "bits",
                "user_id": "1901792",
                "user_name": "cli_user2",
                "user_login": "cli_user2"
            }
        ],
        "last_contribution": {
            "total": 574,
            "type": "bits",
            "user_id": "1901792",
            "user_name": "cli_user2",
            "user_login": "cli_user2"
        },
        "started_at": "2023-11-25T16:35:20.479750187Z",
        "expires_at": "2023-11-25T16:40:20.479750187Z"
    }
}
JSON,
                'type' => 'channel.hype_train.progress',
                'signature' => 'sha256=911130339074f77f3f9b5c5aa86b2417c70c41cf8dd2244d26d3fe65a673974c',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\HypeTrainProgressEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\HypeTrainProgressSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.hype_train.end' => [
                // @TODO: I've modified the response here. Yet again, the Twitch docs differ from the mock-api. Need to
                //        check what the actual response here is
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "f0d94d8b-5dea-5171-943f-542e0ef12622",
        "status": "enabled",
        "type": "channel.hype_train.end",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "25079702"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T16:35:40.741957544Z",
        "cost": 0
    },
    "event": {
        "id": "f0d94d8b-5dea-5171-943f-542e0ef12622",
        "broadcaster_user_id": "25079702",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "level": 1,
        "total": 832,
        "top_contributions": [
            {
                "total": 337,
                "type": "other",
                "user_id": "74183051",
                "user_name": "cli_user1",
                "user_login": "cli_user1"
            },
            {
                "total": 275,
                "type": "bits",
                "user_id": "31241253",
                "user_name": "cli_user2",
                "user_login": "cli_user2"
            }
        ],
        "started_at": "2023-11-25T16:30:40.741957544Z",
        "ended_at": "2023-11-25T16:35:40.741957544Z",
        "cooldown_ends_at": "2023-11-25T17:35:40.741957544Z"
    }
}
JSON,
                'type' => 'channel.hype_train.end',
                'signature' => 'sha256=4e63bd51ee52a1190f23f28ff947de13158fcd02254defea62287e5bbeb75eab',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\HypeTrainEndEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\HypeTrainEndSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.moderator.add' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "73fb097f-3af6-a939-f021-a7790c681b34",
        "status": "enabled",
        "type": "channel.moderator.add",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "21126136"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:11:41.86715917Z",
        "cost": 0
    },
    "event": {
        "user_id": "16817732",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "21126136",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster"
    }
}
JSON,
                'type' => 'channel.moderator.add',
                'signature' => 'sha256=056be47c61cab3f08ebe8754c1e8f8958383677815aa7ae0e03c94825722fb16',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelModeratorAddEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelModeratorAddSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.moderator.remove' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "11945137-cafe-ec39-5d52-0114f68ed648",
        "status": "enabled",
        "type": "channel.moderator.remove",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "80412189"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:16:43.610808547Z",
        "cost": 0
    },
    "event": {
        "user_id": "27933223",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "80412189",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster"
    }
}
JSON,
                'type' => 'channel.moderator.remove',
                'signature' => 'sha256=b4a8f4464120f0535a50ac953decd3922b4c7d837488767c7dfd2436453a8742',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelModeratorRemoveEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelModeratorRemoveSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.poll.begin' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "c3da3c9b-20e9-61fc-3e2f-e98270efe2e5",
        "status": "enabled",
        "type": "channel.poll.begin",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "75584166"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:24:42.865790975Z",
        "cost": 0
    },
    "event": {
        "id": "9cd3be71-60a1-c836-1656-85d3fcac991f",
        "broadcaster_user_id": "75584166",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Pineapple on pizza?",
        "choices": [
            {
                "id": "b49de15a-03c6-267e-2da3-80c57cd1652e",
                "title": "Yes but choice 1"
            },
            {
                "id": "3337294d-cf7c-69b8-68c2-29ae2616fabe",
                "title": "Yes but choice 2"
            },
            {
                "id": "cdd483c8-03c9-0a90-38f5-f67723f2d7d5",
                "title": "Yes but choice 3"
            },
            {
                "id": "c9b87e92-bd8e-1a50-d14b-c8c2745a2dd2",
                "title": "Yes but choice 4"
            }
        ],
        "bits_voting": {
            "is_enabled": true,
            "amount_per_vote": 10
        },
        "channel_points_voting": {
            "is_enabled": true,
            "amount_per_vote": 500
        },
        "started_at": "2023-11-25T19:24:42.865790975Z",
        "ends_at": "2023-11-25T19:39:42.865790975Z"
    }
}
JSON,
                'type' => 'channel.poll.begin',
                'signature' => 'sha256=9ff39b8362cd7d7f10b958c78571d2a14ce444ecc111b24d5d8eb42ead3930ce',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelPollBeginEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelPollBeginSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.poll.progress' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "ffe053cb-aee6-e920-d8d3-f187903a4104",
        "status": "enabled",
        "type": "channel.poll.progress",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "51178477"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:33:18.434519576Z",
        "cost": 0
    },
    "event": {
        "id": "3cf33959-e1e7-0112-17f9-788892f3ab97",
        "broadcaster_user_id": "51178477",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Pineapple on pizza?",
        "choices": [
            {
                "id": "b0a89db2-713b-014e-9585-214c7031e641",
                "title": "Yes but choice 1",
                "bits_votes": 9,
                "channel_points_votes": 6,
                "votes": 21
            },
            {
                "id": "4cd1f964-a5c4-536f-2a9c-f5f0a82c9583",
                "title": "Yes but choice 2",
                "bits_votes": 3,
                "channel_points_votes": 5,
                "votes": 8
            },
            {
                "id": "0f72b0ed-297b-eb41-dfb1-588aace4477c",
                "title": "Yes but choice 3",
                "bits_votes": 8,
                "channel_points_votes": 9,
                "votes": 25
            },
            {
                "id": "6bd3135b-bce9-3b95-d877-c499ada8e8b8",
                "title": "Yes but choice 4",
                "bits_votes": 6,
                "channel_points_votes": 5,
                "votes": 20
            }
        ],
        "bits_voting": {
            "is_enabled": true,
            "amount_per_vote": 10
        },
        "channel_points_voting": {
            "is_enabled": true,
            "amount_per_vote": 500
        },
        "started_at": "2023-11-25T19:33:18.434519576Z",
        "ends_at": "2023-11-25T19:48:18.434519576Z"
    }
}
JSON,
                'type' => 'channel.poll.progress',
                'signature' => 'sha256=0f7c40800b25a28bf429a8b031377d0439c8da55aa7c41780231cd2694008287',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelPollProgressEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelPollProgressSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.poll.end' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "099295d1-d651-52e5-a9f6-f226e6d0a846",
        "status": "enabled",
        "type": "channel.poll.end",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "41770397"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:33:44.138460915Z",
        "cost": 0
    },
    "event": {
        "id": "b96ec445-4e99-eaf1-9b21-5e0643c7d3a0",
        "broadcaster_user_id": "41770397",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Pineapple on pizza?",
        "choices": [
            {
                "id": "c37d79ee-98a1-e9fe-a97c-d2b3a26f9c7a",
                "title": "Yes but choice 1",
                "bits_votes": 4,
                "channel_points_votes": 2,
                "votes": 6
            },
            {
                "id": "6ee9161b-cdce-1f09-cfcc-1461fed37f03",
                "title": "Yes but choice 2",
                "bits_votes": 2,
                "channel_points_votes": 2,
                "votes": 6
            },
            {
                "id": "3afde8ff-63c5-7613-89b6-153d63025f6d",
                "title": "Yes but choice 3",
                "bits_votes": 2,
                "channel_points_votes": 0,
                "votes": 8
            },
            {
                "id": "17d5754a-dea3-54d0-d8f9-932c2975d873",
                "title": "Yes but choice 4",
                "bits_votes": 3,
                "channel_points_votes": 9,
                "votes": 20
            }
        ],
        "bits_voting": {
            "is_enabled": true,
            "amount_per_vote": 10
        },
        "channel_points_voting": {
            "is_enabled": true,
            "amount_per_vote": 500
        },
        "status": "completed",
        "started_at": "2023-11-25T19:33:44.138460915Z",
        "ended_at": "2023-11-25T19:48:44.138460915Z"
    }
}
JSON,
                'type' => 'channel.poll.end',
                'signature' => 'sha256=b99901a45a53a30e0f683a28cbb883b5c9f91579e929d55b0476fd7262afc668',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelPollEndEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelPollEndSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.prediction.begin' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "07f3ccfb-b9da-9acb-cf43-6853985368f1",
        "status": "enabled",
        "type": "channel.prediction.begin",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "6187656"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:36:43.274259799Z",
        "cost": 0
    },
    "event": {
        "id": "9280af32-c619-2595-8a3b-fb0a694e3b49",
        "broadcaster_user_id": "6187656",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Will the developer finish this program?",
        "outcomes": [
            {
                "id": "a6a165d6-081f-0093-2a5d-5ff2b2343c6a",
                "title": "yes",
                "color": "blue"
            },
            {
                "id": "ab5dacfb-73de-3430-5718-eabe15ecbb9e",
                "title": "no",
                "color": "pink"
            }
        ],
        "started_at": "2023-11-25T19:36:43.274259799Z",
        "locks_at": "2023-11-25T19:46:43.274259799Z"
    }
}
JSON,
                'type' => 'channel.prediction.begin',
                'signature' => 'sha256=bc70b96bf243f3d01aae2d9d7e53320f06ff47befdae17caf0da40f023c4140c',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelPredictionBeginEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelPredictionBeginSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.prediction.progress' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "4f4a7ffb-b491-649b-d4f6-19aad6c6bd95",
        "status": "enabled",
        "type": "channel.prediction.progress",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "85476104"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:36:48.20197751Z",
        "cost": 0
    },
    "event": {
        "id": "b23bc16b-24e7-2d19-6070-825d873cb166",
        "broadcaster_user_id": "85476104",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Will the developer finish this program?",
        "outcomes": [
            {
                "id": "d958307e-f1e2-4a9d-d228-34ce8e2a4ec5",
                "title": "yes",
                "color": "blue",
                "users": 5,
                "channel_points": 17170,
                "top_predictors": [
                    {
                        "user_id": "4274899",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 320
                    },
                    {
                        "user_id": "15456684",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 3042
                    },
                    {
                        "user_id": "46126779",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 5850
                    },
                    {
                        "user_id": "39602147",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 6751
                    },
                    {
                        "user_id": "8714596",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 1207
                    }
                ]
            },
            {
                "id": "a41f6fc6-6d87-4329-047f-fbf14e727465",
                "title": "no",
                "color": "pink",
                "users": 4,
                "channel_points": 21357,
                "top_predictors": [
                    {
                        "user_id": "14099994",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 6013
                    },
                    {
                        "user_id": "5288274",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 718
                    },
                    {
                        "user_id": "52130113",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 6366
                    },
                    {
                        "user_id": "68665506",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 8260
                    }
                ]
            }
        ],
        "started_at": "2023-11-25T19:36:48.20197751Z",
        "locks_at": "2023-11-25T19:46:48.20197751Z"
    }
}
JSON,
                'type' => 'channel.prediction.progress',
                'signature' => 'sha256=262cd99aaeb7ad87e5f1b9d8bdcf81e73577eddfb62a4acc3066682b6b5cf9ce',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelPredictionProgressEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelPredictionProgressSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.prediction.lock' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "db4a2d39-a16b-9e61-42e1-b354e4de5f6f",
        "status": "enabled",
        "type": "channel.prediction.lock",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "85556217"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:45:12.265929654Z",
        "cost": 0
    },
    "event": {
        "id": "a5b889ff-092a-4539-4696-ade815ed5aba",
        "broadcaster_user_id": "85556217",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Will the developer finish this program?",
        "outcomes": [
            {
                "id": "fbf30b19-e147-e34f-c3b7-bd60e505b436",
                "title": "yes",
                "color": "blue",
                "users": 7,
                "channel_points": 37767,
                "top_predictors": [
                    {
                        "user_id": "12925465",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 6594
                    },
                    {
                        "user_id": "31227106",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 8177
                    },
                    {
                        "user_id": "69209440",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 4820
                    },
                    {
                        "user_id": "9064083",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 3875
                    },
                    {
                        "user_id": "3733954",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 5140
                    },
                    {
                        "user_id": "60333124",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 4726
                    },
                    {
                        "user_id": "11689445",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 4435
                    }
                ]
            },
            {
                "id": "3ec38b4f-0c2e-fd57-0c25-b6a7a707fc1e",
                "title": "no",
                "color": "pink",
                "users": 4,
                "channel_points": 24601,
                "top_predictors": [
                    {
                        "user_id": "24235895",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 4674
                    },
                    {
                        "user_id": "33300473",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 7058
                    },
                    {
                        "user_id": "24110244",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 7256
                    },
                    {
                        "user_id": "9364990",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": null,
                        "channel_points_used": 5613
                    }
                ]
            }
        ],
        "started_at": "2023-11-25T19:45:12.265929654Z",
        "locked_at": "2023-11-25T19:55:12.265929654Z"
    }
}
JSON,
                'type' => 'channel.prediction.lock',
                'signature' => 'sha256=2c3568b49d6e7113f8e20ccf96f6b5b528f13b166a25dd29207399048e453165',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelPredictionLockEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelPredictionLockSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.prediction.end' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "d9153825-877e-a6da-5998-e3e89e01bf14",
        "status": "enabled",
        "type": "channel.prediction.end",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "44260258"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:36:50.298377044Z",
        "cost": 0
    },
    "event": {
        "id": "572465bc-6f42-8472-b7b4-a18907821e73",
        "broadcaster_user_id": "44260258",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Will the developer finish this program?",
        "winning_outcome_id": "72dd7609-2558-2588-215f-d657195703fd",
        "outcomes": [
            {
                "id": "72dd7609-2558-2588-215f-d657195703fd",
                "title": "yes",
                "color": "blue",
                "users": 4,
                "channel_points": 21064,
                "top_predictors": [
                    {
                        "user_id": "87323307",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": 16502,
                        "channel_points_used": 8251
                    },
                    {
                        "user_id": "63076574",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": 2094,
                        "channel_points_used": 1047
                    },
                    {
                        "user_id": "17219367",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": 12454,
                        "channel_points_used": 6227
                    },
                    {
                        "user_id": "62301789",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": 11078,
                        "channel_points_used": 5539
                    }
                ]
            },
            {
                "id": "453cc1a7-7ccf-defb-23f3-563a16b87c12",
                "title": "no",
                "color": "pink",
                "users": 2,
                "channel_points": 5042,
                "top_predictors": [
                    {
                        "user_id": "96438806",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": 0,
                        "channel_points_used": 1380
                    },
                    {
                        "user_id": "93187080",
                        "user_login": "testLogin",
                        "user_name": "testLogin",
                        "channel_points_won": 0,
                        "channel_points_used": 3662
                    }
                ]
            }
        ],
        "started_at": "2023-11-25T19:36:50.298377044Z",
        "ended_at": "2023-11-25T19:46:50.298377044Z",
        "status": "resolved"
    }
}
JSON,
                'type' => 'channel.prediction.end',
                'signature' => 'sha256=c156e8c3c61bd9bbf0d216a185e719a43aa743abfb8fb9438e397acd79d2c557',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelPredictionEndEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelPredictionEndSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.raid' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "533e7156-48f1-ca03-c1e6-76d190c89714",
        "status": "enabled",
        "type": "channel.raid",
        "version": "1",
        "condition": {
            "to_broadcaster_user_id": "74254333"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:47:47.171021438Z",
        "cost": 0
    },
    "event": {
        "to_broadcaster_user_id": "74254333",
        "to_broadcaster_user_login": "testBroadcaster",
        "to_broadcaster_user_name": "testBroadcaster",
        "from_broadcaster_user_id": "88886508",
        "from_broadcaster_user_login": "testFromUser",
        "from_broadcaster_user_name": "testFromUser",
        "viewers": 99696
    }
}
JSON,
                'type' => 'channel.raid',
                'signature' => 'sha256=28221ac1370d6bbbb2e57ec1fc513b7b086b79653543c27b4b7dcdd0a847ad93',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelRaidEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelRaidSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.shield_mode.begin' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "d5d057c1-4070-2d85-4ca5-b7f644c5d299",
        "status": "enabled",
        "type": "channel.shield_mode.begin",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "91483760",
            "moderator_user_id": "64791255"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:52:09.385986887Z",
        "cost": 0
    },
    "event": {
        "broadcaster_user_id": "91483760",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "moderator_user_id": "64791255",
        "moderator_user_name": "testFromUser",
        "moderator_user_login": "testFromUser",
        "started_at": "2023-11-25T19:42:09.386021501Z"
    }
}
JSON,
                'type' => 'channel.shield_mode.begin',
                'signature' => 'sha256=75f642ba1ffbdead40757eab449a9561709afd99a662580b365769e4a18b94c4',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ShieldModeEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ShieldModeBeginSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.shield_mode.end' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "0636eeb1-0786-f3d7-f606-deeac9c40e27",
        "status": "enabled",
        "type": "channel.shield_mode.end",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "56790761",
            "moderator_user_id": "73567388"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-25T19:52:59.602082313Z",
        "cost": 0
    },
    "event": {
        "broadcaster_user_id": "56790761",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "moderator_user_id": "73567388",
        "moderator_user_name": "testFromUser",
        "moderator_user_login": "testFromUser",
        "ended_at": "2023-11-25T19:52:59.602091262Z"
    }
}
JSON,
                'type' => 'channel.shield_mode.end',
                'signature' => 'sha256=8059b23c7c638d77efa83eedb8940febfe7b189afe7fbcf700c033aafb8a029e',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ShieldModeEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ShieldModeEndSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.shoutout.create' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "c8acb7fc-1202-3556-0b75-9dd714a2ca56",
        "status": "enabled",
        "type": "channel.shoutout.create",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "95045965",
            "moderator_user_id": "3502151007"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:39:00.032845817Z",
        "cost": 0
    },
    "event": {
        "broadcaster_user_id": "95045965",
        "broadcaster_user_name": "testFromUser",
        "broadcaster_user_login": "testFromUser",
        "to_broadcaster_user_id": "46637545",
        "to_broadcaster_user_name": "testBroadcaster",
        "to_broadcaster_user_login": "testBroadcaster",
        "moderator_user_id": "3502151007",
        "moderator_user_name": "TrustedUser123",
        "moderator_user_login": "trusteduser123",
        "viewer_count": 1376,
        "started_at": "2023-11-26T14:39:00.032855579Z",
        "cooldown_ends_at": "2023-11-26T14:41:00.032855579Z",
        "target_cooldown_ends_at": "2023-11-26T15:39:00.032855579Z"
    }
}
JSON,
                'type' => 'channel.shoutout.create',
                'signature' => 'sha256=c375fe65fddf04c2e8c24b9b2dfd37794ca7a277839a2f6aa17fafe6b1d1440b',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ShoutoutCreateEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ShoutoutCreateSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.shoutout.receive' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "910934b4-d9f6-f367-b273-ea31260f9231",
        "status": "enabled",
        "type": "channel.shoutout.receive",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "87101336",
            "moderator_user_id": "3502151007"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:40:07.344381085Z",
        "cost": 0
    },
    "event": {
        "broadcaster_user_id": "87101336",
        "broadcaster_user_name": "testBroadcaster",
        "broadcaster_user_login": "testBroadcaster",
        "from_broadcaster_user_id": "78362404",
        "from_broadcaster_user_name": "testFromUser",
        "from_broadcaster_user_login": "testFromUser",
        "viewer_count": 605,
        "started_at": "2023-11-26T14:40:07.34439031Z"
    }
}
JSON,
                'type' => 'channel.shoutout.receive',
                'signature' => 'sha256=bdce28caeb20a404f8f50226442924f1ff62b9f521c960ad87647d7ab38620b3',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ShoutoutReceiveEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ShoutoutReceiveSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.subscribe' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "7a1141c4-b754-a21e-24e3-aa665a03143f",
        "status": "enabled",
        "type": "channel.subscribe",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "84728067"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:41:12.728004676Z",
        "cost": 0
    },
    "event": {
        "user_id": "87663790",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "84728067",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "tier": "1000",
        "is_gift": false
    }
}
JSON,
                'type' => 'channel.subscribe',
                'signature' => 'sha256=536225714d2c1f5e2d87666f731c25aa7300e1571972727b7906d18d48f666d4',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelSubscribeEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelSubscribeSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.subscription.end' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "6dbd9c7e-ac73-a135-16cc-d0ddc6aad4c8",
        "status": "enabled",
        "type": "channel.subscription.end",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "39639988"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:42:13.344955837Z",
        "cost": 0
    },
    "event": {
        "user_id": "37190197",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "39639988",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "tier": "1000",
        "is_gift": false
    }
}
JSON,
                'type' => 'channel.subscription.end',
                'signature' => 'sha256=339d9621e91cbd867b3f61cff1e1ef9b63c4dcfc845fc38125b4cde7cb088a82',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelSubscriptionEndEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelSubscriptionEndSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.subscription.gift' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "56c115cc-79ba-1a2b-8f15-ad78971de322",
        "status": "enabled",
        "type": "channel.subscription.gift",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "18904039"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:43:09.176125416Z",
        "cost": 0
    },
    "event": {
        "user_id": "73904106",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "18904039",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "tier": "1000",
        "total": 5,
        "is_anonymous": false,
        "cumulative_total": 44
    }
}
JSON,
                'type' => 'channel.subscription.gift',
                'signature' => 'sha256=d1646d48690cf421f7aba50814dbbe2db46a5e0166a6e1ad8e8265f5e36107fb',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelSubscriptionGiftEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelSubscriptionGiftSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.subscription.message' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "1aea0058-1993-e3b1-93b2-b7cc99850c74",
        "status": "enabled",
        "type": "channel.subscription.message",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "89946795"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:44:00.593121192Z",
        "cost": 0
    },
    "event": {
        "user_id": "6307759",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "89946795",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "tier": "1000",
        "message": {
            "text": "Hello from the Twitch CLI! twitchdevLeek",
            "emotes": [
                {
                    "begin": 26,
                    "end": 39,
                    "id": "304456816"
                }
            ]
        },
        "cumulative_months": 65,
        "streak_months": 61,
        "duration_months": 1
    }
}
JSON,
                'type' => 'channel.subscription.message',
                'signature' => 'sha256=0012f58f2c57dfb51a38c3b55a380753db2f8d94f04c92652b863e31e495d424',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelSubscriptionMessageEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelSubscriptionMessageSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.unban' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "6b2059ca-b36e-e3c4-20d2-7d1b0ef49839",
        "status": "enabled",
        "type": "channel.unban",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "74588498"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:44:27.327828226Z",
        "cost": 0
    },
    "event": {
        "user_id": "16771802",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "broadcaster_user_id": "74588498",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "moderator_user_id": "37292467",
        "moderator_user_login": "CLIModerator",
        "moderator_user_name": "CLIModerator"
    }
}
JSON,
                'type' => 'channel.unban',
                'signature' => 'sha256=7fb48a96b0de5c2b44763edfdfe01b0d02e3897ec4b4c42f9dfb167f607f4ea7',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelUnbanEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelUnbanSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'channel.update' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "113cad55-2359-4bd4-d33f-a6f19798ede8",
        "status": "enabled",
        "type": "channel.update",
        "version": "2",
        "condition": {
            "broadcaster_user_id": "53812473"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:45:21.696375061Z",
        "cost": 0
    },
    "event": {
        "broadcaster_user_id": "53812473",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "title": "Example title from the CLI!",
        "language": "en",
        "category_id": "4727",
        "category_name": "Just Chatting",
        "content_classification_labels": [
            "MatureGame",
            "ViolentGraphic"
        ]
    }
}
JSON,
                'type' => 'channel.update',
                'signature' => 'sha256=4c52cb88dd9c750bd0ab7838dc14048d8863b77feafc8d54c4b264efbdcdb76f',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\ChannelUpdateEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\ChannelUpdateSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'drop.entitlement.grant' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "e2352f94-3110-5499-4dbe-d32d1f416aa5",
        "status": "enabled",
        "type": "drop.entitlement.grant",
        "version": "1",
        "condition": {
            "organization_id": "96369184",
            "category_id": "5173",
            "campaign_id": "72feb4e7-e49c-a539-9709-f839da53db24"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:48:19.704502718Z",
        "cost": 0
    },
    "events": [
        {
            "id": "44b17f8d-f29c-e301-0473-bae11de29d88",
            "data": {
                "entitlement_id": "da61ef23-df10-5929-4fef-9650c45bb449",
                "benefit_id": "bb334ecd-e6c6-b4e7-6ce3-22c4433828ad",
                "campaign_id": "72feb4e7-e49c-a539-9709-f839da53db24",
                "organization_id": "96369184",
                "created_at": "2023-11-26T14:48:19.704502718Z",
                "user_id": "8854960",
                "user_name": "testBroadcaster",
                "user_login": "testBroadcaster",
                "category_id": "5173",
                "category_name": "Special Events"
            }
        }
    ]
}
JSON,
                'type' => 'drop.entitlement.grant',
                'signature' => 'sha256=0e7b2c613f58db8dca60be687ec7b2dc0b9005abd0bbd3b1738959674eba6f25',
                'assertions' => function (MultipleEventResponse $eventResponse) {
                    self::assertContainsOnlyInstancesOf(
                        Events\DropEntitlementGrantEvent::class,
                        $eventResponse->getEvents()
                    );
                    self::assertInstanceOf(
                        Subscriptions\DropEntitlementGrantSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'extension.bits_transaction.create' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "def71f34-035f-a205-ae73-75062428d506",
        "status": "enabled",
        "type": "extension.bits_transaction.create",
        "version": "1",
        "condition": {
            "extension_client_id": "3khakej2oj7x1ooow43to4gob2sy5d"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:50:50.713071524Z",
        "cost": 1
    },
    "event": {
        "id": "def71f34-035f-a205-ae73-75062428d506",
        "extension_client_id": "3khakej2oj7x1ooow43to4gob2sy5d",
        "broadcaster_user_id": "96274370",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "user_name": "testUser",
        "user_login": "testUser",
        "user_id": "41115980",
        "product": {
            "name": "Test Trigger Item from CLI",
            "sku": "testItemSku",
            "bits": 100,
            "in_development": true
        }
    }
}
JSON,
                'type' => 'extension.bits_transaction.create',
                'signature' => 'sha256=6e437f345532534fe62bcccabe00bf02f3d0ebd712112d0593dacd4ea7a36f0c',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(
                        Events\ExtensionBitsTransactionCreateEvent::class,
                        $eventResponse->getEvent()
                    );
                    self::assertInstanceOf(
                        Subscriptions\ExtensionBitsTransactionCreateSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'stream.offline' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "d333f05b-197c-ad7b-5766-ce4b5ebbe89d",
        "status": "enabled",
        "type": "stream.offline",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "18467193"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:51:16.184376075Z",
        "cost": 0
    },
    "event": {
        "broadcaster_user_id": "18467193",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster"
    }
}
JSON,
                'type' => 'stream.offline',
                'signature' => 'sha256=674076d5e1532c1938211ef70981e139adc34c658c6bdfc8c6c76ea7026fe279',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\StreamOfflineEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\StreamOfflineSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'stream.online' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "50bf8fbe-3e75-fc0f-6a0c-b796d8bf4950",
        "status": "enabled",
        "type": "stream.online",
        "version": "1",
        "condition": {
            "broadcaster_user_id": "60931478"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:55:03.5764177Z",
        "cost": 0
    },
    "event": {
        "id": "71641010",
        "broadcaster_user_id": "60931478",
        "broadcaster_user_login": "testBroadcaster",
        "broadcaster_user_name": "testBroadcaster",
        "type": "live",
        "started_at": "2023-11-26T14:55:03.5764177Z"
    }
}
JSON,
                'type' => 'stream.online',
                'signature' => 'sha256=25fa3566dbce4a718b9121a80255ed0da36030bf27508ebc3793d597cf4ed9a6',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\StreamOnlineEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\StreamOnlineSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'user.authorization.grant' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "8d17457c-9d89-99e9-9e60-ccff7d7a64cc",
        "status": "enabled",
        "type": "user.authorization.grant",
        "version": "1",
        "condition": {
            "client_id": "3khakej2oj7x1ooow43to4gob2sy5d"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:55:33.672467292Z",
        "cost": 1
    },
    "event": {
        "user_id": "49299545",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "client_id": "3khakej2oj7x1ooow43to4gob2sy5d"
    }
}
JSON,
                'type' => 'user.authorization.grant',
                'signature' => 'sha256=ef45918f15006102770382a3e84f41ab41c3a899ebfefd6319c0be3390d84c51',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\UserAuthorizationGrantEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\UserAuthorizationGrantSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'user.authorization.revoke' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "54ec7223-8b9e-93ed-3f6d-62bec4869e99",
        "status": "enabled",
        "type": "user.authorization.revoke",
        "version": "1",
        "condition": {
            "client_id": "3khakej2oj7x1ooow43to4gob2sy5d"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:56:28.705098085Z",
        "cost": 1
    },
    "event": {
        "user_id": "52295089",
        "user_login": "testFromUser",
        "user_name": "testFromUser",
        "client_id": "3khakej2oj7x1ooow43to4gob2sy5d"
    }
}
JSON,
                'type' => 'user.authorization.revoke',
                'signature' => 'sha256=35ef56e24302595a5166bf82c3125a6ec04a46a522b61665cc269bc53625661a',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\UserAuthorizationRevokeEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\UserAuthorizationRevokeSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
            'user.update' => [
                'event' => <<<'JSON'
{
    "subscription": {
        "id": "a934c642-568d-f0ba-eaa8-518a9f59e41a",
        "status": "enabled",
        "type": "user.update",
        "version": "1",
        "condition": {
            "user_id": "75897416"
        },
        "transport": {
            "method": "webhook",
            "callback": "null"
        },
        "created_at": "2023-11-26T14:57:21.512583701Z",
        "cost": 0
    },
    "event": {
        "user_id": "75897416",
        "user_login": "testBroadcaster",
        "user_name": "testBroadcaster",
        "email": "stream-lover@example.com",
        "email_verified": true,
        "description": ""
    }
}
JSON,
                'type' => 'user.update',
                'signature' => 'sha256=aba431acadd95e4cc58a947a8c14f2998f194dd3d839f818e59e259f597349b6',
                'assertions' => function (EventResponse $eventResponse) {
                    self::assertInstanceOf(Events\UserUpdateEvent::class, $eventResponse->getEvent());
                    self::assertInstanceOf(
                        Subscriptions\UserUpdateSubscription::class,
                        $eventResponse->getSubscription()
                    );
                },
            ],
        ];
    }

    /**
     * @param string $event
     * @param string $type
     * @param string $signature
     * @param callable $assertions
     *
     * @return void
     * @throws MappingError
     * @throws InvalidSource
     * @throws ChallengeMissingException
     * @throws InvalidSignatureException
     * @throws UnsupportedEventException
     * @dataProvider handleSubscriptionCallbackSuccessDataProvider
     */
    public function testHandleSubscriptionCallbackSuccess(
        string $event,
        string $type,
        string $signature,
        callable $assertions
    ) {
        $client = new Client();
        $psr17Factory = new Psr17Factory();
        $mapperBuilder = new MapperBuilder();
        $request = $psr17Factory->createRequest('POST', 'http://localhost/check/twitch')
            ->withBody(Stream::create($event))
            ->withHeader('Twitch-Eventsub-Message-Signature', $signature)
            ->withHeader('Twitch-Eventsub-Message-Id', '123456')
            ->withHeader('Twitch-Eventsub-Subscription-Type', $type)
            ->withHeader('Twitch-Eventsub-Message-Timestamp', 'test');

        $apiClient = new ApiClient($client, $psr17Factory, $mapperBuilder, $psr17Factory);
        $eventSubApi = new EventSubApi($apiClient);
        $sut = new EventSubService($eventSubApi, $mapperBuilder, ['webhook' => ['secret' => '1234567890']]);
        $eventResponse = $sut->handleSubscriptionCallback($request);

        self::assertNull($eventResponse->getChallenge());
        $assertions($eventResponse);
    }
}
