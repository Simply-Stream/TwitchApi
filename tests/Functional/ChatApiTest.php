<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ChatApi;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChannelEmote;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatBadge;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatColorEnum;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChatSettings;
use SimplyStream\TwitchApi\Helix\Models\Chat\Chatter;
use SimplyStream\TwitchApi\Helix\Models\Chat\EmoteSet;
use SimplyStream\TwitchApi\Helix\Models\Chat\GlobalEmote;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;
use SimplyStream\TwitchApi\Helix\Models\Chat\SendChatAnnouncementRequest;
use SimplyStream\TwitchApi\Helix\Models\Chat\UpdateChatSettingsRequest;
use SimplyStream\TwitchApi\Helix\Models\Chat\Version;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchTemplatedDataResponse;

class ChatApiTest extends UserAwareFunctionalTestCase
{
    public function testGetChatters()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $getChattersResponse = $chatApi->getChatters(
            $testUser['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:read:chatters']))
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getChattersResponse);
        $this->assertCount(25, $getChattersResponse->getData());
        $this->assertSame(25, $getChattersResponse->getTotal());
        $this->assertContainsOnlyInstancesOf(Chatter::class, $getChattersResponse->getData());

        foreach ($getChattersResponse->getData() as $chatter) {
            $this->assertIsString($chatter->getUserId());
            $this->assertNotEmpty($chatter->getUserId());
            $this->assertIsString($chatter->getUserLogin());
            $this->assertNotEmpty($chatter->getUserLogin());
            $this->assertIsString($chatter->getUserName());
            $this->assertNotEmpty($chatter->getUserName());
        }
    }

    public function testGetChannelEmotes()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $channelEmotesResponse = $chatApi->getChannelEmotes($testUser['id'], new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchTemplatedDataResponse::class, $channelEmotesResponse);
        $this->assertCount(15, $channelEmotesResponse->getData());
        $this->assertContainsOnlyInstancesOf(ChannelEmote::class, $channelEmotesResponse->getData());

        foreach ($channelEmotesResponse->getData() as $channelEmote) {
            $this->assertIsString($channelEmote->getId());
            $this->assertNotEmpty($channelEmote->getId());
            $this->assertIsString($channelEmote->getName());
            $this->assertNotEmpty($channelEmote->getName());

            // Images
            $this->assertInstanceOf(Image::class, $channelEmote->getImages());
            $this->assertIsString($channelEmote->getImages()->getUrl1x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl1x());
            $this->assertIsString($channelEmote->getImages()->getUrl2x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl2x());
            $this->assertIsString($channelEmote->getImages()->getUrl4x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl4x());
            //

            $this->assertIsArray($channelEmote->getFormat());
            $this->assertSame(['static', 'animated'], $channelEmote->getFormat());

            $this->assertIsArray($channelEmote->getScale());
            $this->assertSame(['1.0', '2.0', '3.0'], $channelEmote->getScale());

            $this->assertIsArray($channelEmote->getThemeMode());
            $this->assertSame(['light', 'dark'], $channelEmote->getThemeMode());

            $this->assertIsString($channelEmote->getTier());
            $this->assertIsString($channelEmote->getEmoteType());
            $this->assertIsString($channelEmote->getEmoteSetId());
        }

        $this->assertIsString($channelEmotesResponse->getTemplate());
        $this->assertNotEmpty($channelEmotesResponse->getTemplate());
    }

    public function testGetGlobalEmotes()
    {
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $globalEmotesResponse = $chatApi->getGlobalEmotes(new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchTemplatedDataResponse::class, $globalEmotesResponse);
        $this->assertCount(100, $globalEmotesResponse->getData());
        $this->assertContainsOnlyInstancesOf(GlobalEmote::class, $globalEmotesResponse->getData());

        foreach ($globalEmotesResponse->getData() as $channelEmote) {
            $this->assertIsString($channelEmote->getId());
            $this->assertNotEmpty($channelEmote->getId());
            $this->assertIsString($channelEmote->getName());
            $this->assertNotEmpty($channelEmote->getName());

            // Images
            $this->assertInstanceOf(Image::class, $channelEmote->getImages());
            $this->assertIsString($channelEmote->getImages()->getUrl1x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl1x());
            $this->assertIsString($channelEmote->getImages()->getUrl2x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl2x());
            $this->assertIsString($channelEmote->getImages()->getUrl4x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl4x());
            //

            $this->assertIsArray($channelEmote->getFormat());
            $this->assertSame(['static'], $channelEmote->getFormat());

            $this->assertIsArray($channelEmote->getScale());
            $this->assertSame(['1.0', '2.0', '3.0'], $channelEmote->getScale());

            $this->assertIsArray($channelEmote->getThemeMode());
            $this->assertSame(['light', 'dark'], $channelEmote->getThemeMode());
        }

        $this->assertIsString($globalEmotesResponse->getTemplate());
        $this->assertNotEmpty($globalEmotesResponse->getTemplate());
    }

    public function testGetEmoteSets()
    {
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $emoteSetResponse = $chatApi->getEmoteSets(1, new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchTemplatedDataResponse::class, $emoteSetResponse);
        $this->assertCount(15, $emoteSetResponse->getData());
        $this->assertContainsOnlyInstancesOf(EmoteSet::class, $emoteSetResponse->getData());

        foreach ($emoteSetResponse->getData() as $channelEmote) {
            $this->assertIsString($channelEmote->getId());
            $this->assertNotEmpty($channelEmote->getId());
            $this->assertIsString($channelEmote->getName());
            $this->assertNotEmpty($channelEmote->getName());

            // Images
            $this->assertInstanceOf(Image::class, $channelEmote->getImages());
            $this->assertIsString($channelEmote->getImages()->getUrl1x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl1x());
            $this->assertIsString($channelEmote->getImages()->getUrl2x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl2x());
            $this->assertIsString($channelEmote->getImages()->getUrl4x());
            $this->assertNotEmpty($channelEmote->getImages()->getUrl4x());
            //

            $this->assertIsArray($channelEmote->getFormat());
            $this->assertSame(['static', 'animated'], $channelEmote->getFormat());

            $this->assertIsArray($channelEmote->getScale());
            $this->assertSame(['1.0', '2.0', '3.0'], $channelEmote->getScale());

            $this->assertIsArray($channelEmote->getThemeMode());
            $this->assertSame(['light', 'dark'], $channelEmote->getThemeMode());
        }

        $this->assertIsString($emoteSetResponse->getTemplate());
        $this->assertNotEmpty($emoteSetResponse->getTemplate());
    }

    public function testGetChannelChatBadges()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $channelChatBadgesResponse = $chatApi->getChannelChatBadges(
            $testUser['id'],
            new AccessToken($this->appAccessToken)
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $channelChatBadgesResponse);

        $this->assertCount(1, $channelChatBadgesResponse->getData());
        $this->assertContainsOnlyInstancesOf(ChatBadge::class, $channelChatBadgesResponse->getData());
        $channelChatBadges = $channelChatBadgesResponse->getData()[0];
        $this->assertSame('subscriber', $channelChatBadges->getSetId());
        $this->assertCount(15, $channelChatBadges->getVersions());
        $this->assertContainsOnlyInstancesOf(Version::class, $channelChatBadges->getVersions());

        foreach ($channelChatBadges->getVersions() as $chatBadge) {
            $this->assertIsString($chatBadge->getId());
            // We need to check for a 0 as a string, because PHPs empty-function will incorrectly return true here,
            // which will cause this check to fail then, even though 0 is a valid ID (at least for the mock-api)
            if ($chatBadge->getId() !== '0') {
                $this->assertNotEmpty($chatBadge->getId());
            }
            $this->assertIsString($chatBadge->getImageUrl1x());
            $this->assertNotEmpty($chatBadge->getImageUrl1x());
            $this->assertIsString($chatBadge->getImageUrl2x());
            $this->assertNotEmpty($chatBadge->getImageUrl2x());
            $this->assertIsString($chatBadge->getImageUrl4x());
            $this->assertNotEmpty($chatBadge->getImageUrl4x());
            $this->assertIsString($chatBadge->getTitle());
            $this->assertNotEmpty($chatBadge->getTitle());
            $this->assertIsString($chatBadge->getDescription());
            $this->assertNotEmpty($chatBadge->getDescription());
            $this->assertIsString($chatBadge->getClickAction());
            $this->assertNotEmpty($chatBadge->getClickAction());
        }
    }

    public function testGetGlobalChatBadges()
    {
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $channelChatBadgesResponse = $chatApi->getGlobalChatBadges(new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchDataResponse::class, $channelChatBadgesResponse);

        $this->assertCount(6, $channelChatBadgesResponse->getData());
        $this->assertContainsOnlyInstancesOf(ChatBadge::class, $channelChatBadgesResponse->getData());

        foreach ($channelChatBadgesResponse->getData() as $channelChatBadges) {
            $this->assertIsString($channelChatBadges->getSetId());
            $this->assertNotEmpty($channelChatBadges->getSetId());
            $this->assertGreaterThan(0, count($channelChatBadges->getVersions()));
            $this->assertContainsOnlyInstancesOf(Version::class, $channelChatBadges->getVersions());

            foreach ($channelChatBadges->getVersions() as $chatBadge) {
                $this->assertIsString($chatBadge->getId());
                // We need to check for a 0 as a string, because PHPs empty-function will incorrectly return true here,
                // which will cause this check to fail then, even though 0 is a valid ID (at least for the mock-api)
                if ($chatBadge->getId() !== '0') {
                    $this->assertNotEmpty($chatBadge->getId());
                }
                $this->assertIsString($chatBadge->getImageUrl1x());
                $this->assertNotEmpty($chatBadge->getImageUrl1x());
                $this->assertIsString($chatBadge->getImageUrl2x());
                $this->assertNotEmpty($chatBadge->getImageUrl2x());
                $this->assertIsString($chatBadge->getImageUrl4x());
                $this->assertNotEmpty($chatBadge->getImageUrl4x());
                $this->assertIsString($chatBadge->getTitle());
                $this->assertNotEmpty($chatBadge->getTitle());
                $this->assertIsString($chatBadge->getDescription());
                $this->assertNotEmpty($chatBadge->getDescription());
            }
        }
    }

    public function testGetChatSettings()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $getChatSettingsResponse = $chatApi->getChatSettings($testUser['id'], new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchDataResponse::class, $getChatSettingsResponse);
        $this->assertCount(1, $getChatSettingsResponse->getData());
        $this->assertContainsOnlyInstancesOf(ChatSettings::class, $getChatSettingsResponse->getData());

        foreach ($getChatSettingsResponse->getData() as $chatSettings) {
            $this->assertSame($testUser['id'], $chatSettings->getBroadcasterId());
            $this->assertNull($chatSettings->getModeratorId());
            $this->assertIsBool($chatSettings->isEmoteMode());
            $this->assertIsBool($chatSettings->isFollowerMode());
            $this->assertSame(60, $chatSettings->getFollowerModeDuration());
            $this->assertIsBool($chatSettings->isSlowMode());
            $this->assertSame(10, $chatSettings->getSlowModeWaitTime());
            $this->assertIsBool($chatSettings->isSubscriberMode());
            $this->assertIsBool($chatSettings->isUniqueChatMode());
            $this->assertNull($chatSettings->getNonModeratorChatDelay());
            $this->assertNull($chatSettings->getNonModeratorChatDelayDuration());
        }
    }

    public function testUpdatechatSettings()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $getChatSettingsResponse = $chatApi->getChatSettings($testUser['id'], new AccessToken($this->appAccessToken));

        $this->assertFalse($getChatSettingsResponse->getData()[0]->isFollowerMode());

        $chatApi->updateChatSettings(
            $testUser['id'],
            $testUser['id'],
            new UpdateChatSettingsRequest(followerMode: true),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:chat_settings']))
        );

        $getChatSettingsResponse = $chatApi->getChatSettings($testUser['id'], new AccessToken($this->appAccessToken));

        $this->assertTrue($getChatSettingsResponse->getData()[0]->isFollowerMode());

        $chatApi->updateChatSettings(
            $testUser['id'],
            $testUser['id'],
            new UpdateChatSettingsRequest(followerMode: false),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:chat_settings']))
        );
    }

    public function testSendChatAnnouncement()
    {
        // Unless the request throws an exception, this test is perfectly valid
        $this->expectNotToPerformAssertions();

        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $chatApi->sendChatAnnouncement(
            $testUser['id'],
            $testUser['id'],
            new SendChatAnnouncementRequest('Test message'),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:announcements']))
        );
    }

    public function testSendShoutout()
    {
        $this->markTestSkipped('Waiting for https://github.com/twitchdev/twitch-cli/pull/301 to be approved');

        // Unless the request throws an exception, this test is perfectly valid
        $this->expectNotToPerformAssertions();

        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $chatApi->sendShoutout(
            $testUser['id'],
            $this->users[1]['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:shoutouts']))
        );
    }

    public function testGetUserChatColor()
    {
        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $getUserChatColorResponse = $chatApi->getUserChatColor($testUser['id'], new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchDataResponse::class, $getUserChatColorResponse);
        $this->assertIsArray($getUserChatColorResponse->getData());
        $this->assertCount(1, $getUserChatColorResponse->getData());
        $chatColor = $getUserChatColorResponse->getData()[0];

        $this->assertSame($testUser['id'], $chatColor->getUserId());
        $this->assertSame($testUser['login'], $chatColor->getUserLogin());
        $this->assertSame($testUser['display_name'], $chatColor->getUserName());
        $this->assertNotEquals("#FF7F50", $chatColor->getColor());
    }

    public function testUpdateUserChatColor()
    {
        $this->markTestSkipped('Mock-Api returns wrong Statuscode');

        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $chatApi = new ChatApi($apiClient);
        $getUserChatColorResponse = $chatApi->getUserChatColor($testUser['id'], new AccessToken($this->appAccessToken));
        $this->assertNotEquals("#FF7F50", $getUserChatColorResponse->getData()[0]->getColor());

        $chatApi->updateUserChatColor(
            $testUser['id'],
            ChatColorEnum::CORAL,
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['user:manage:chat_color']))
        );
        $getUserChatColorResponse = $chatApi->getUserChatColor($testUser['id'], new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchDataResponse::class, $getUserChatColorResponse);
        $this->assertIsArray($getUserChatColorResponse->getData());
        $this->assertCount(1, $getUserChatColorResponse->getData());
        $chatColor = $getUserChatColorResponse->getData()[0];

        $this->assertSame($testUser['id'], $chatColor->getUserId());
        $this->assertSame($testUser['login'], $chatColor->getUserLogin());
        $this->assertSame($testUser['display_name'], $chatColor->getUserName());
        $this->assertSame("#FF7F50", $chatColor->getColor());

        $chatApi->updateUserChatColor(
            $testUser['id'],
            ChatColorEnum::BLUE,
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['user:manage:chat_color']))
        );
    }
}
