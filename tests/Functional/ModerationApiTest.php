<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use Nyholm\Psr7\Factory\Psr17Factory;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ModerationApi;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BanUser;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BanUserRequest;
use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatusRequest;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ManageHeldAutoModMessageRequest;
use SimplyStream\TwitchApi\Helix\Models\Moderation\Moderator;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ShieldModeStatus;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateShieldModeStatusRequest;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UserBan;
use SimplyStream\TwitchApi\Helix\Models\Moderation\VIP;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class ModerationApiTest extends UserAwareFunctionalTestCase
{
    public function testCheckAutoModStatus(): void
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

        $moderationApi = new ModerationApi($apiClient);
        $checkAutoModStatusResponse = $moderationApi->checkAutoModStatus(
            $testUser['id'],
            new CheckAutoModStatusRequest([new CheckAutoModStatus('123', 'Hello')]),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderation:read']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $checkAutoModStatusResponse);
        $this->assertContainsOnlyInstancesOf(AutoModStatus::class, $checkAutoModStatusResponse->getData());

        foreach ($checkAutoModStatusResponse->getData() as $autoModStatus) {
            $this->assertSame('123', $autoModStatus->getMsgId());
            $this->assertIsBool($autoModStatus->isPermitted());
        }
    }

    public function testManageHeldAutoModMessages(): void
    {
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

        $moderationApi = new ModerationApi($apiClient);
        $moderationApi->manageHeldAutoModMessages(
            new ManageHeldAutoModMessageRequest($testUser['id'], '123', 'ALLOW'),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:automod']))
        );
    }

    public function testGetAutoModSettings(): void
    {
        $this->markTestSkipped('"moderation/automod/settings" endpoint does not exist in mock-api');

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

        $moderationApi = new ModerationApi($apiClient);
        $moderationApi->getAutoModSettings(
            $testUser['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:read:automod_settings']))
        );
    }

    public function testUpdateAutoModSettings(): void
    {
        $this->markTestSkipped('"moderation/automod/settings" endpoint does not exist in mock-api');

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

        $moderationApi = new ModerationApi($apiClient);
        $moderationApi->getAutoModSettings(
            $testUser['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:read:automod_settings']))
        );

        // Change received values
    }

    public function testGetBannedUsers(): void
    {
        $this->markTestSkipped('Get banned users mock-api endpoint might not return useful data for this test');

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

        $moderationApi = new ModerationApi($apiClient);
        $getBannedUsersResponse = $moderationApi->getBannedUsers(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderation:read']))
        );
    }

    public function testBanUser(): void
    {
        $testUser = $this->users[0];
        $bannedUser = $this->users[1];
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

        $moderationApi = new ModerationApi($apiClient);
        $banUserResponse = $moderationApi->banUser(
            $testUser['id'],
            $testUser['id'],
            new BanUserRequest(new BanUser($bannedUser['id'])),
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:banned_users']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $banUserResponse);
        $this->assertCount(1, $banUserResponse->getData());
        $this->assertContainsOnlyInstancesOf(UserBan::class, $banUserResponse->getData());

        foreach ($banUserResponse->getData() as $banUser) {
            $this->assertSame($bannedUser['id'], $banUser->getUserId());
            $this->assertSame($testUser['id'], $banUser->getBroadcasterId());
            $this->assertSame($testUser['id'], $banUser->getModeratorId());
            $this->assertInstanceOf(\DateTimeImmutable::class, $banUser->getCreatedAt());
            $this->assertNull($banUser->getEndTime());
        }

        // Unban after testing, to prevent faulty failed tests. Also, this will test indirectly "unbanUser"
        $moderationApi->unbanUser(
            $testUser['id'],
            $testUser['id'],
            $bannedUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:banned_users']))
        );
    }

    public function testGetBlockedTerms(): void
    {
        $this->markTestSkipped('"moderation/blocked_terms" endpoint does not exist on mock-api');

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

        $moderationApi = new ModerationApi($apiClient);
        $getBlockedTermsResponse = $moderationApi->getBlockedTerms(
            $testUser['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:read:blocked_terms']))
        );
    }

    public function testAddBlockedTerms(): void
    {
        $this->markTestSkipped('"moderation/blocked_terms" endpoint does not exist on mock-api');

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

        $moderationApi = new ModerationApi($apiClient);
        $getBlockedTermsResponse = $moderationApi->getBlockedTerms(
            $testUser['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:read:blocked_terms']))
        );
    }

    public function testRemoveBlockedTerms(): void
    {
        $this->markTestSkipped('"moderation/blocked_terms" endpoint does not exist on mock-api');

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

        $moderationApi = new ModerationApi($apiClient);
        $getBlockedTermsResponse = $moderationApi->getBlockedTerms(
            $testUser['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:read:blocked_terms']))
        );
    }

    public function testDeleteChatMessages(): void
    {
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

        $moderationApi = new ModerationApi($apiClient);
        $moderationApi->deleteChatMessages(
            $testUser['id'],
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderator:manage:chat_messages'])),
            '123'
        );
    }

    public function testGetModerators(): void
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

        $moderationApi = new ModerationApi($apiClient);
        $getModeratorsResponse = $moderationApi->getModerators(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['moderation:read']))
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getModeratorsResponse);
        $this->assertContainsOnlyInstancesOf(Moderator::class, $getModeratorsResponse->getData());
        $this->assertGreaterThan(0, count($getModeratorsResponse->getData()));

        foreach ($getModeratorsResponse->getData() as $moderator) {
            $this->assertNotEmpty($moderator->getUserId());
            $this->assertIsString($moderator->getUserId());
            $this->assertNotEmpty($moderator->getUserLogin());
            $this->assertIsString($moderator->getUserLogin());
            $this->assertNotEmpty($moderator->getUserName());
            $this->assertIsString($moderator->getUserName());
        }

        $this->assertNull($getModeratorsResponse->getTotal());
    }

    public function testAddRemoveChannelModerator(): void
    {
        $this->expectNotToPerformAssertions();

        $testUser = $this->users[0];
        $newModerator = $this->users[1];
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

        $accessToken = new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:moderators']));
        $moderationApi = new ModerationApi($apiClient);

        try {
            $moderationApi->removeChannelModerator($testUser['id'], $newModerator['id'], $accessToken);
        } catch (\Exception $exception) {
        }

        $moderationApi->addChannelModerator(
            $testUser['id'],
            $newModerator['id'],
            $accessToken
        );

        $moderationApi->removeChannelModerator(
            $testUser['id'],
            $newModerator['id'],
            $accessToken
        );
    }

    public function testGetVips(): void
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

        $moderationApi = new ModerationApi($apiClient);
        $getVipsResponse = $moderationApi->getVips(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:manage:vips']))
        );

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getVipsResponse);
        $this->assertContainsOnlyInstancesOf(VIP::class, $getVipsResponse->getData());
    }

    public function testAddRemoveVips(): void
    {
        $testUser = $this->users[0];
        $vipUser = $this->users[1];
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

        $accessToken = new AccessToken(
            $this->getAccessTokenForUser($testUser['id'], ['channel:manage:vips', 'channel:manage:moderators'])
        );
        $moderationApi = new ModerationApi($apiClient);
        $getVipsResponse = $moderationApi->getVips($testUser['id'], $accessToken);

        try {
            // The mock data is mostly random, so to be sure, we'll remove the user as a moderator. Twitch doesn't allow
            // a moderator to also be a VIP
            $moderationApi->removeChannelModerator($testUser['id'], $vipUser['id'], $accessToken);
        } catch (\Exception $exception) {
        }

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $getVipsResponse);
        $this->assertContainsOnlyInstancesOf(VIP::class, $getVipsResponse->getData());

        foreach ($getVipsResponse->getData() as $vip) {
            if ($vip->getUserId() === $vipUser['id']) {
                $moderationApi->removeChannelVip($testUser['id'], $vipUser['id'], $accessToken);
            }
        }

        $moderationApi->addChannelVip($testUser['id'], $vipUser['id'], $accessToken);
    }

    public function testUpdateShieldModeStatus(): void
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

        $accessToken = new AccessToken(
            $this->getAccessTokenForUser($testUser['id'], ['moderator:manage:shield_mode'])
        );
        $moderationApi = new ModerationApi($apiClient);
        $getShieldModeStatus = $moderationApi->getShieldModeStatus($testUser['id'], $testUser['id'], $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $getShieldModeStatus);
        $this->assertCount(1, $getShieldModeStatus->getData());
        $this->assertContainsOnlyInstancesOf(ShieldModeStatus::class, $getShieldModeStatus->getData());

        foreach ($getShieldModeStatus->getData() as $shieldModeStatus) {
            $this->assertFalse($shieldModeStatus->isActive());
            $this->assertEmpty($shieldModeStatus->getModeratorId());
            $this->assertEmpty($shieldModeStatus->getModeratorLogin());
            $this->assertEmpty($shieldModeStatus->getModeratorName());
            $this->assertInstanceOf(\DateTimeImmutable::class, $shieldModeStatus->getLastActivatedAt());
        }

        $updateShieldModeStatusResponse = $moderationApi->updateShieldModeStatus(
            $testUser['id'],
            $testUser['id'],
            new UpdateShieldModeStatusRequest(true),
            $accessToken
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $updateShieldModeStatusResponse);
        $this->assertCount(1, $updateShieldModeStatusResponse->getData());
        $this->assertContainsOnlyInstancesOf(ShieldModeStatus::class, $updateShieldModeStatusResponse->getData());

        foreach ($updateShieldModeStatusResponse->getData() as $shieldModeStatus) {
            $this->assertTrue($shieldModeStatus->isActive());
            $this->assertSame($testUser['id'], $shieldModeStatus->getModeratorId());
            $this->assertSame($testUser['login'], $shieldModeStatus->getModeratorLogin());
            $this->assertSame($testUser['display_name'], $shieldModeStatus->getModeratorName());
            $this->assertInstanceOf(\DateTimeImmutable::class, $shieldModeStatus->getLastActivatedAt());
        }
    }
}
