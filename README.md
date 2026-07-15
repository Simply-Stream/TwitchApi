# PHP Twitch API implementation (by Simply-Stream.com)

[![QA](https://github.com/Simply-Stream/TwitchApi/actions/workflows/qa.yaml/badge.svg?branch=main)](https://github.com/Simply-Stream/TwitchApi/actions/workflows/qa.yaml) [![codecov](https://codecov.io/gh/Simply-Stream/TwitchApi/graph/badge.svg?token=HT6IUJKM97)](https://codecov.io/gh/Simply-Stream/TwitchApi)

-------------------------------

## Key Features

**Framework-agnostic**: The library depends on PSR-18 (HTTP client) and PSR-17 (request/stream factories) only. It
contains no framework bindings and instantiates no HTTP client of its own.

**Serializer-independent**: JSON mapping is delegated through two interfaces, `DenormalizerInterface` and
`NormalizerInterface`. A Symfony Serializer bridge is available as a separate package; any other implementation works
as long as it satisfies the two interfaces. The models carry no serializer-specific attributes.

**Helix coverage**: One client class per API namespace (`UsersApi`, `StreamsApi`, `ChannelsApi`, …), each extending
`AbstractApi` and taking the same three constructor arguments. Requests and responses are typed objects; no associative
arrays are returned.

**Typed models**: Responses map to `final readonly` classes with promoted, typed properties. Timestamps are typed as
`DateTimeInterface`. Nullability follows observed API behavior, including fields where Twitch sends an empty string
rather than `null`.

**EventSub webhook pipeline**: `EventSubMessageProcessor` handles signature verification (HMAC-SHA256), message
freshness, deduplication, and type resolution. Subscription type and version are read from the
`Twitch-Eventsub-Subscription-Type` and `-Version` headers, which allows multiple versions of the same type — such as
`channel.moderate` v1 and v2 — to be registered simultaneously.

To see a full list of implemented APIs and EventSub events, have a look at the [Implemented APIs](#implemented-apis)
section.

## Implemented APIs

| API                      | Implemented | Tested |
|--------------------------|-------------|--------|
| AdsApi                   | ✅           | ✅      |
| AnalyticsApi             | ✅           | ✅      |
| BitsApi                  | ✅           | ✅      |
| ChannelPointsApi         | ✅           | ✅      |
| ChannelsApi              | ✅           | ✅      |
| CharityApi               | ✅           | ✅      |
| ChatApi                  | ✅           | ✅      |
| ClipsApi                 | ✅           | ✅     |
| ContentClassificationApi | ✅           | ✅      |
| EntitlementsApi          | ✅           | ℹ️     |
| EventSubApi              | ✅           | ✅      |
| EventSub system          | ✅           | ℹ️     |
| ExtensionsApi            | ❗           | ❗      |
| GamesApi                 | ✅           | ✅      |
| GoalsApi                 | ✅           | ✅      |
| GuestStarApi (Beta)      | ✅           | ✅      |
| HypeTrainApi             | ✅           | ✅      |
| ModerationApi            | ✅           | ✅      |
| PollsApi                 | ✅           | ✅      |
| PredictionsApi           | ✅           | ✅      |
| RaidsApi                 | ✅           | ✅      |
| ScheduleApi              | ✅           | ✅      |
| SearchApi                | ✅           | ✅      |
| StreamsApi               | ✅           | ✅      |
| SubscriptionsApi         | ✅           | ✅      |
| TeamsApi                 | ✅           | ✅      |
| UsersApi                 | ✅           | ✅      |
| VideosApi                | ✅           | ✅      |
| WhispersApi              | ✅           | ✅      |

✅ = Implemented, covered by unit and integration tests  
❗ = Not migrated to v2 / not covered
ℹ️ = Implemented and covered, with known open questions:

- **EntitlementsApi** — `ProductData` may use camelCase keys (`inDevelopment`, `displayName`). Never verified against
  real payloads.
- **EventSub system** — the message pipeline (signature verification, freshness, type registry, denormalization,
  dispatch) is covered by functional roundtrip tests, but only for the event types the Twitch CLI can trigger. See the
  table below.

## EventSub Events

| Event                                                            | Modelled | Roundtrip test |
|------------------------------------------------------------------|----------|----------------|
| automod.message.hold (v1, v2)                                     | ✅        | ❗              |
| automod.message.update (v1, v2)                                   | ✅        | ❗              |
| automod.settings.update                                           | ✅        | ❗              |
| automod.terms.update                                              | ✅        | ❗              |
| channel.ad_break.begin                                            | ✅        | ✅              |
| channel.ban                                                       | ✅        | ✅              |
| channel.bits.use                                                  | ✅        | ❗              |
| channel.channel_points_automatic_reward_redemption.add (v1, v2)   | ✅        | ❗              |
| channel.channel_points_custom_reward.add / remove / update        | ✅        | ✅              |
| channel.channel_points_custom_reward_redemption.add / update      | ✅        | ✅              |
| channel.charity_campaign.donate / progress / start / stop         | ✅        | ✅              |
| channel.chat.clear                                                | ✅        | ❗              |
| channel.chat.clear_user_messages                                  | ✅        | ❗              |
| channel.chat.message                                              | ✅        | ❗              |
| channel.chat.message_delete                                       | ✅        | ❗              |
| channel.chat.notification                                         | ✅        | ❗              |
| channel.chat.user_message_hold / update                           | ✅        | ❗              |
| channel.chat_settings.update                                      | ✅        | ❗              |
| channel.cheer                                                     | ✅        | ✅              |
| channel.custom_power_up_redemption.add                            | ✅        | ❗              |
| channel.follow (v2)                                               | ✅        | ✅              |
| channel.goal.begin / end / progress                               | ✅        | ✅              |
| channel.guest_star_guest.update (Beta)                            | ✅        | ❗              |
| channel.guest_star_session.begin / end (Beta)                     | ✅        | ❗              |
| channel.guest_star_settings.update (Beta)                         | ✅        | ❗              |
| channel.hype_train.begin / end / progress (v2)                    | ✅        | ℹ️             |
| channel.moderate (v1, v2)                                         | ✅        | ❗              |
| channel.moderator.add / remove                                    | ✅        | ✅              |
| channel.poll.begin / end / progress                               | ✅        | ✅              |
| channel.prediction.begin / end / lock / progress                  | ✅        | ✅              |
| channel.raid                                                      | ✅        | ✅              |
| channel.shared_chat.begin / end / update                          | ✅        | ❗              |
| channel.shield_mode.begin / end                                   | ✅        | ✅              |
| channel.shoutout.create / receive                                 | ✅        | ✅              |
| channel.subscribe                                                 | ✅        | ✅              |
| channel.subscription.end / gift / message                         | ✅        | ✅              |
| channel.suspicious_user.message / update                          | ✅        | ❗              |
| channel.unban                                                     | ✅        | ✅              |
| channel.unban_request.create                                      | ✅        | ℹ️             |
| channel.unban_request.resolve                                     | ✅        | ✅              |
| channel.update (v2)                                               | ✅        | ✅              |
| channel.vip.add / remove                                          | ✅        | ❗              |
| channel.warning.acknowledge / send                                | ✅        | ❗              |
| conduit.shard.disabled                                            | ✅        | ❗              |
| drop.entitlement.grant                                            | ✅        | ✅              |
| extension.bits_transaction.create                                 | ✅        | ✅              |
| stream.offline / online                                           | ✅        | ✅              |
| user.authorization.grant / revoke                                 | ✅        | ✅              |
| user.update                                                       | ✅        | ✅              |
| user.whisper.message                                              | ✅        | ❗              |

✅ = Modeled from the Twitch docs and exercised by a functional roundtrip test against a captured, signed webhook
fixture  
❗ = Modeled, but untested: `twitch event trigger` cannot produce this type, so no signed fixture exists  
ℹ️ = Tested against a hand-patched fixture:

- **channel.hype_train.\*** — the CLI only emits v1 payloads. The v2 fixtures were derived from the v1 capture plus the
  documented v2 fields; `all_time_high_level` and `all_time_high_total` carry invented values.
- **channel.unban_request.create** — the CLI omits `event.id`, which the docs list as required. Added by hand.

> **Note:** "Modeled ✅" means the class matches the field-level documentation, not that it has been verified against
> production payloads. Twitch's example payloads proved unreliable throughout; the field tables are the authoritative
> source.

## Usage

### Getting started

#### Installation

```bash
composer req simplystream/twitch-api
```

#### Creating an API client

The library is framework-agnostic and ships no HTTP client of its own. Bring your own PSR-18 client and PSR-17
factories, plus a serializer that implements the library's `DenormalizerInterface` and `NormalizerInterface`.

```php
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\UsersApi;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\GetUsersRequest;

$psr17 = new Psr17Factory();
$httpClient = new Symfony\Component\HttpClient\Psr18Client();

$apiClient = new ApiClient(
    httpClient: $httpClient,
    requestFactory: $psr17,
    streamFactory: $psr17,
    clientId: 'your-client-id',
);

// Any object implementing both DenormalizerInterface and NormalizerInterface.
// A ready-made Symfony Serializer setup will be provided by the serializer bridge
// package; see "Serialization".
$serializer = /* ... */;

$usersApi = new UsersApi($apiClient, $serializer, $serializer);

$response = $usersApi->getUsers(
    new GetUsersRequest(logins: ['twitchdev']),
    $accessToken,
);

foreach ($response->data as $user) {
    echo $user->displayName, ' — ', $user->broadcasterType, PHP_EOL;
}
```

We recommend either using the all-in-one package [Guzzlehttp](https://packagist.org/packages/guzzlehttp/guzzle]) or [PHP-HTTP](https://packagist.org/packages/php-http/curl-client) with the PSR7 implementation 
[Nyholm/PSR7](https://github.com/Nyholm/psr7) or [Guzzle/PSR7](https://packagist.org/packages/guzzlehttp/psr7).

Every namespace of the Helix API has its own slim client class — `UsersApi`, `StreamsApi`, `ChannelsApi`, and so on.
They all take the same three constructor arguments, so wiring them up in a DI container is a one-liner per class.

Each method takes a request object and an access token:

```php
use SimplyStream\TwitchApi\Helix\Api\StreamsApi;
use SimplyStream\TwitchApi\Helix\Api\Streams\Request\GetStreamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Streams\StreamType;

$streamsApi = new StreamsApi($apiClient, $serializer, $serializer);

$streams = $streamsApi->getStreams(
    new GetStreamsRequest(
        userLogins: ['twitchdev', 'twitch'],
        type: StreamType::Live,
        first: 50,
    ),
    $accessToken,
);

echo $streams->data[0]->title;
echo $streams->pagination?->cursor;
```

### Access tokens

This library does not implement the OAuth flow. Getting and refreshing tokens is up to you — use any OAuth client you
like, or Twitch's own endpoints directly.

What the library needs is an implementation of `AccessTokenInterface`, which it uses to build the `Authorization`
header. Wrapping a token string you already hold is enough:

```php
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final readonly class MyAccessToken implements AccessTokenInterface
{
    public function __construct(
        private string $token,
    ) {
    }

    public function getAccessToken(): string
    {
        return $this->token;
    }
}
```

Which scopes a call requires is documented on each API method. Most read endpoints accept an app access token; anything
that acts on a broadcaster's behalf needs a user access token with the matching scope.

Extension endpoints expect a JWT signed with your extension secret instead of an OAuth token. The library treats both
the same way — wrap whichever token you hold in an `AccessTokenInterface` implementation. Creating the JWT is up to you.

### Custom base URL

The `$baseUrl` constructor argument defaults to `https://api.twitch.tv/helix` and can be pointed elsewhere — at the
Twitch CLI's mock API, for instance:

```php
$apiClient = new ApiClient(
    httpClient: $httpClient,
    requestFactory: $psr17,
    streamFactory: $psr17,
    clientId: 'your-client-id',
    baseUrl: 'http://localhost:8080/mock',
);
```

### Serialization

*To be documented once the serializer bridge package is available.*

## Supported Frameworks

Currently, there is only an integration for [Symfony](https://symfony.com).

- [simplystream/twitch-api-bundle](https://github.com/Simply-Stream/TwitchApiBundle) (Still WIP, most of the code there
  has been moved to this repository)

## Contribution

We welcome contributions! Feel free to open issues, submit pull requests, or join our community discussions.
A short guide for contribution will follow.

## Support

You really like this project and want to support us differently than contribution?
Feel free to support me on Ko-fi ♥️

[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/R6R0HV2IO)
