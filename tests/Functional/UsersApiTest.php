<?php

namespace SimplyStream\TwitchApiBundle\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use InvalidArgumentException;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApiBundle\Helix\Api\ApiClient;
use SimplyStream\TwitchApiBundle\Helix\Api\UsersApi;
use SimplyStream\TwitchApiBundle\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApiBundle\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\UpdateUserExtension;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\User;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\UserActiveExtension;

class UsersApiTest extends FunctionalTestCase
{
    public function testGetUsers() {
        $mockUser = $this->users[0];

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $usersResponse = $usersApi->getUsers(logins: [$mockUser['login']], accessToken: new AccessToken($this->appAccessToken));

        $this->assertInstanceOf(TwitchDataResponse::class, $usersResponse);
        $this->assertIsArray($usersResponse->getData());
        $this->assertCount(1, $usersResponse->getData());

        foreach ($usersResponse->getData() as $user) {
            $this->assertInstanceOf(User::class, $user);
            $this->assertSame($mockUser['id'], $user->getId());
            $this->assertSame($mockUser['login'], $user->getLogin());
            $this->assertSame($mockUser['display_name'], $user->getDisplayName());
            $this->assertSame($mockUser['broadcaster_type'], $user->getBroadcasterType());
            $this->assertSame($mockUser['description'], $user->getDescription());
            $this->assertSame($mockUser['view_count'], $user->getViewCount());
            // The Mock-API won't give us the image URL in advance, so we just want to know if it is a non-empty-string
            $this->assertIsString($user->getProfileImageUrl());
            $this->assertNotEmpty($user->getProfileImageUrl());
            $this->assertIsString($user->getOfflineImageUrl());
            $this->assertNotEmpty($user->getOfflineImageUrl());

            $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
            $this->assertEquals(new \DateTimeImmutable($mockUser['created_at']), $user->getCreatedAt());
        }
    }

    public function testGetUSersThrowsExceptionWhenIdOrLoginsIsNotSet() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You need to specify at least one "id" or "login"');

        $client = new Client();
        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $usersApi->getUsers(accessToken: new AccessToken($this->appAccessToken));
    }

    /**
     * @return void
     * @dataProvider getUsersThrowsExceptionWhenMoreThan100UsersAreRequestedDataProvider
     * @throws \JsonException
     */
    public function testGetUsersThrowsExceptionWhenMoreThan100UsersAreRequested(array $ids, array $logins) {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You can only request a total amount of 100 users at once');

        $client = new Client();
        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $usersApi->getUsers(ids: $ids, logins: $logins);
    }

    public function testUpdateUser() {
        $mockUser = $this->users[0];

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $accessToken = new AccessToken($this->getAccessTokenForUser($mockUser['id'], ['user:edit']));

        // Using the same string over and over could result in false positives, so we just generate some random string
        $newDescription = bin2hex(random_bytes(50));
        $updateUserResponse = $usersApi->updateUser($accessToken, $newDescription);

        $this->assertInstanceOf(TwitchDataResponse::class, $updateUserResponse);
        $this->assertCount(1, $updateUserResponse->getData());

        foreach ($updateUserResponse->getData() as $user) {
            $this->assertInstanceOf(User::class, $user);
            $this->assertSame($mockUser['id'], $user->getId());
            $this->assertSame($mockUser['login'], $user->getLogin());
            $this->assertSame($mockUser['display_name'], $user->getDisplayName());
            $this->assertSame($mockUser['type'], $user->getType());
            $this->assertSame($mockUser['broadcaster_type'], $user->getBroadcasterType());
            $this->assertNotSame($mockUser['description'], $user->getDescription());
            $this->assertSame($newDescription, $user->getDescription());
            // The Mock-API won't give us the image URL in advance, so we just want to know if it is a non-empty-string
            $this->assertIsString($user->getProfileImageUrl());
            $this->assertNotEmpty($user->getProfileImageUrl());
            $this->assertIsString($user->getOfflineImageUrl());
            $this->assertNotEmpty($user->getOfflineImageUrl());

            $this->assertSame($mockUser['view_count'], $user->getViewCount());
            $this->assertInstanceOf(\DateTimeImmutable::class, $user->getCreatedAt());
            $this->assertEquals(new \DateTimeImmutable($mockUser['created_at']), $user->getCreatedAt());
        }
    }

    public function testUpdateUserThrowsExceptionWhenDescriptionIsTooLong() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A description can not be longer than 300 characters');

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $accessToken = new AccessToken(['access_token' => 'doesn\'t matter, will throw an exception beforehand', 'token_type' => 'bearer']);

        $usersApi->updateUser($accessToken, 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum');
    }

    public function testBlockUser() {
        // There won't be any response object to check. The test is considered successful, when no exception is thrown
        $this->expectNotToPerformAssertions();

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $usersApi->blockUser($this->users[1]['id'], new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:manage:blocked_users'])));
    }

    public function testGetUsersBlockList() {
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:read:blocked_users']));
        $userBlockListResponse = $usersApi->getUserBlockList($this->users[0]['id'], $accessToken);

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $userBlockListResponse);
        $this->assertCount(1, $userBlockListResponse->getData());

        foreach ($userBlockListResponse->getData() as $block) {
            $this->assertSame($this->users[1]['id'], $block->getUserId());
            $this->assertSame($this->users[1]['login'], $block->getUserLogin());
            $this->assertSame($this->users[1]['display_name'], $block->getDisplayName());
        }

        // Because we are using the mock-api, these values are not set (yet).
        // $this->assertInstanceOf(Pagination::class, $userBlockListResponse->getPagination());
        // $this->assertIsString($userBlockListResponse->getPagination()->getCursor());
    }

    public function testUnblockUser() {
        // There won't be any response object to check (yet). The test is considered successful, when no exception is thrown
        $this->expectNotToPerformAssertions();

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $usersApi = new UsersApi($apiClient);
        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:manage:blocked_users']));
        $usersApi->unblockUser($this->users[1]['id'], $accessToken);
    }

    public function testGetUserExtensions() {
        $this->markTestSkipped('"/users/extensions/list" endpoint does not exist on mock-api');

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        $usersApi = new UsersApi($apiClient);
        $usersExtensionResponse = $usersApi->getUserExtensions($accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $usersExtensionResponse);
        $this->assertCount(1, $usersExtensionResponse->getData());

        foreach ($usersExtensionResponse->getData() as $extension) {
            $this->assertInstanceOf(UserExtension::class, $extension);
            $this->assertTrue($extension->canActivate());
            $this->assertEquals('123456', $extension->getId());
        }
    }

    public function testGetUserActiveExtensionsWithUserToken() {
        $this->markTestSkipped('"/users/extensions" endpoint does not exist on mock-api');

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        $usersApi = new UsersApi($apiClient);
        $usersGetActiveExtensions = $usersApi->getUserActiveExtensions(accessToken: $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $usersGetActiveExtensions);
        $this->assertIsNotArray($usersGetActiveExtensions->getData());

        foreach ($usersGetActiveExtensions->getData() as $extension) {
            $this->assertInstanceOf(UserActiveExtension::class, $extension);
        }
    }

    public function testGetUserActiveExtensionsWithAppToken() {
        $this->markTestSkipped('"/users/extensions" endpoint does not exist on mock-api');

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        $usersApi = new UsersApi($apiClient);
        $usersGetActiveExtensions = $usersApi->getUserActiveExtensions('123456789', accessToken: $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $usersGetActiveExtensions);
        $this->assertIsNotArray($usersGetActiveExtensions->getData());

        foreach ($usersGetActiveExtensions->getData() as $extension) {
            $this->assertInstanceOf(UserActiveExtension::class, $extension);
        }
    }

    public function testUpdateUserExtensions() {
        // @TODO: Create an extension and validate this test
        $this->markTestSkipped('This test "might" be correct, but I can\'t actually validate the response.');

        $updateUserExtensionsBody = new UpdateUserExtension(json_decode(<<<'JSON'
{"data":{"panel":{"1":{"active":true,"id":"123","version":"1.2.5","name":"Some Extension"}}}}
JSON
            , true));

        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            $this->createTwitchProvider(),
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        $usersApi = new UsersApi($apiClient);
        $updateUserExtensionsResponse = $usersApi->updateUserExtensions($updateUserExtensionsBody, $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $updateUserExtensionsResponse);

        $data = $updateUserExtensionsResponse->getData();

        $this->assertInstanceOf(UserActiveExtension::class, $data);
        $this->assertIsArray($data->getPanel());

        foreach ($data->getPanel() as $panel) {
            $this->assertInstanceOf(Panel::class, $panel);
        }

        $this->assertIsArray($data->getOverlay());

        foreach ($data->getOverlay() as $overlay) {
            $this->assertInstanceOf(Overlay::class, $overlay);
        }

        $this->assertIsArray($data->getComponent());

        foreach ($data->getComponent() as $component) {
            $this->assertInstanceOf(Component::class, $component);
        }
    }

    public function getUsersThrowsExceptionWhenMoreThan100UsersAreRequestedDataProvider() {
        // Just generate some strings, the content doesn't matter, we only need more than 100 keys
        return [
            'Test with 101 IDs' => [
                'id' => array_fill(0, 101, uniqid()),
                'logins' => []
            ],
            'Test with 101 logins' => [
                'id' => [],
                'logins' => array_fill(0, 101, uniqid()),
            ],
            'Test with 50 ids and 51 logins' => [
                'id' => array_fill(0, 50, uniqid()),
                'logins' => array_fill(0, 51, uniqid()),
            ]
        ];
    }
}