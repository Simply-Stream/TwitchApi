# PHP Twitch API implementation (by Simply-Stream.com)

[![QA](https://github.com/Simply-Stream/TwitchApi/actions/workflows/qa.yaml/badge.svg?branch=main)](https://github.com/Simply-Stream/TwitchApi/actions/workflows/qa.yaml) [![codecov](https://codecov.io/gh/Simply-Stream/TwitchApi/graph/badge.svg?token=HT6IUJKM97)](https://codecov.io/gh/Simply-Stream/TwitchApi)

-------------------------------

Welcome to the PHP Twitch Helix API Library, a powerful and developer-friendly implementation of the new Twitch API "
Helix," complete with robust EventSub functionality. This library seamlessly integrates the latest features from Twitch,
providing a straightforward and efficient way to interact with the Twitch platform in your PHP projects.

## Key Features

**Helix API Support**: Harness the full potential of the Twitch Helix API with ease. Retrieve user information, access
streams, and more, all through a clean and intuitive PHP interface.

**EventSub Functionality**: Embrace the future of Twitch event handling with our comprehensive EventSub implementation.
Keep your application in sync with real-time events, ensuring timely and accurate updates.

**Webhook Integration**: Our library fully supports webhook communication for EventSub, enabling seamless communication
between Twitch and your application. Stay informed about user activities and channel events effortlessly.

**Data Transfer Objects (DTOs)**: Differentiating itself from traditional approaches, our library employs Data Transfer
Objects (DTOs) to map incoming JSON responses to PHP objects. This abstraction simplifies the handling of Twitch data,
enhancing code readability and maintainability.

To see a full list of implemented APIs, have a look at the [Implemented APIs](#implemented-apis) section.

## Installation

```bash
composer req simplystream/twitch-api
```

## Implemented APIs

| API                      | Implemented | Tested |
|--------------------------|-------------|--------|
| AdsApi                   | ✅           | ℹ️     |
| AnalyticsApi             | ✅           | ❗      |
| BitsApi                  | ✅           | ℹ️     |
| ChannelPointsApi         | ✅           | ✅      |
| ChannelsApi              | ✅           | ✅      |
| CharityApi               | ✅           | ✅      |
| ChatApi                  | ✅           | ✅      |
| ClipsApi                 | ✅           | ✅      |
| ContentClassificationApi | ✅           | ✅      |
| EntitlementsApi          | ✅           | ✅      |
| EventSubApi              | ✅           | ❗      |
| EventSub system          | ✅           | ✅      |
| ExtensionsApi            | ✅           | ❗      |
| GamesApi                 | ✅           | ✅      |
| GoalsApi                 | ✅           | ✅      |
| GuestStarApi (Beta)      | ✅           | ❗      |
| HypeTrainApi             | ✅           | ✅      |
| ModerationApi            | ✅           | ℹ️     |
| PollsApi                 | ✅           | ✅      |
| PredictionsApi           | ✅           | ✅      |
| RaidsApi                 | ✅           | ✅      |
| ScheduleApi              | ✅           | ✅      |
| SearchApi                | ✅           | ✅      |
| StreamsApi               | ✅           | ✅      |
| SubscriptionsApi         | ✅           | ✅      |
| TeamsApi                 | ✅           | ✅      |
| UsersApi                 | ✅           | ℹ️     |
| VideosApi                | ✅           | ✅      |
| WhispersApi              | ✅           | ✅      |

❗ = Tests can't be implemented due to lack of mock-api-data. Mapping should work on Twitch prod systems
️️ℹ️ = Some tests are available, some are missing due to lack of mock-api data. Mapping should work on Twitch prod
systems

**Tested in this case means, that functional or unit tests exist.**

There's also a container api service that can hold all the APIs implemented.
See [TwitchApi](src/Helix/Api/TwitchApi.php).

### EventSub

Besides the APIs, there's also a service available for the EventSub handling. This service will handle the registration
to an event and also the webhook callbacks by validating the challenge send by Twitch.

**Please note, that this package only supports the webhook implementation!**
This is due to the fact, that PHP might not be the ideal programming language to use for long running processes like a
websocket.

#### Websocket

To use the websocket implementation, you should check out the following projects:

- [TwitchLib (C#)](https://github.com/TwitchLib/TwitchLib)
- [Twurple (TypeScript)](https://github.com/twurple/twurple)
  and [docs](https://twurple.js.org/docs/getting-data/eventsub/listener-setup.html)
- More will follow

## Usage

To get everything up and running, you need to set some things up.

```php
$client = new Client();
// Same for the request factory, it just needs to implement the RequestFactoryInterface&StreamFactoryInterface.
// Optionally the UriFactoryInterface, too, if you want to use the same object for the UriFactory.
$requestFactory = new RequestFactory();

$apiClient = new ApiClient(
    $client,
    $requestFactory,
    new \CuyZ\Valinor\MapperBuilder(),
    $requestFactory,
    ['clientId' => 'YOUR_CLIENT_ID', 'webhook' => ['secret' => 'YOUR_SECRET']]
);

$usersApi = new UsersApi($apiClient)
$response = $usersApi->getUsers(logins: ['some_login_name'], accessToken: $accessToken);

foreach($response->getData() as $user) {
    echo $user->getDisplayName();
}
```

### Bring your own client

Instead of forcing you to implement yet another HTTP client, this library gives you the opportunity to use your own.
The only restriction you got: It has to be PSR-18 compliant and implement the
interface `\Psr\Http\Client\ClientInterface`.

We recommend either using the all in one package [Guzzlehttp](https://packagist.org/packages/guzzlehttp/guzzle])
or [PHP-HTTP](https://packagist.org/packages/php-http/curl-client) with the PSR7
implementation [Nyholm/PSR7](https://github.com/Nyholm/psr7)
or [Guzzle/PSR7](https://packagist.org/packages/guzzlehttp/psr7).

### AccessToken

In older versions, the AccessToken have been autogenerated by the sendRequest method by a TwitchProvider that has been
build into this library. This provider is now removed and you need to give the `$api->sendRequest(...)` method an
AccessToken yourself!

Due to the fact that this library still requires the PHP league implementation of an AccessTokenInterface, we recommend
using https://github.com/vertisan/oauth2-twitch-helix to generate an AccessToken.

## Supported Frameworks

Currently, there is only an integration for [Symfony](https://symfony.com).

- [simplystream/twitch-api-bundle](https://github.com/Simply-Stream/TwitchApiBundle) (Still WIP, most of the code there
  has been moved to this repository)

## TODO List

Even though most of this library is ready to use, there's still a lot to do. Here is a brief overview of what will come
next ordered more or less by priority:

- Factories/Builder to easily instantiate the APIs and maybe some DTOs, especially the TwitchApi class
- Middleware features, to easily extend requests (e.g.: RateLimitMiddleware)
- A guideline for contributions

## Contribution

We welcome contributions! Feel free to open issues, submit pull requests, or join our community discussions.
A short guide for contribution will follow.

## Support

You really like this project and want to support us in a different way than contribution?
Feel free to support me on Ko-fi ♥️

[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/R6R0HV2IO)
