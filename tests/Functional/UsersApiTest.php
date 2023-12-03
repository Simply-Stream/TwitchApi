<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use InvalidArgumentException;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\Attributes\CoversClass;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\UsersApi;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Helix\Models\Users\User;

#[CoversClass(UsersApi::class)]
class UsersApiTest extends UserAwareFunctionalTestCase
{
    public static function getUsersThrowsExceptionWhenMoreThan100UsersAreRequestedDataProvider(): array
    {
        // Just generate some strings, the content doesn't matter, we only need more than 100 keys
        return [
            'Test with 101 IDs' => [
                'id' => array_fill(0, 101, uniqid('', true)),
                'logins' => [],
            ],
            'Test with 101 logins' => [
                'id' => [],
                'logins' => array_fill(0, 101, uniqid('', true)),
            ],
            'Test with 50 ids and 51 logins' => [
                'id' => array_fill(0, 50, uniqid('', true)),
                'logins' => array_fill(0, 51, uniqid('', true)),
            ],
        ];
    }

    public function testGetUsers(): void
    {
        $mockUser = $this->users[0];

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

        $usersApi = new UsersApi($apiClient);
        $usersResponse = $usersApi->getUsers(
            logins: [$mockUser['login']],
            accessToken: new AccessToken(
                $this->appAccessToken
            )
        );

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

    public function testGetUSersThrowsExceptionWhenIdOrLoginsIsNotSet(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You need to specify at least one "id" or "login"');

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

        $usersApi = new UsersApi($apiClient);
        $usersApi->getUsers(accessToken: new AccessToken($this->appAccessToken));
    }

    /**
     * @param array $ids
     * @param array $logins
     *
     * @return void
     * @throws \JsonException
     * @dataProvider getUsersThrowsExceptionWhenMoreThan100UsersAreRequestedDataProvider
     */
    public function testGetUsersThrowsExceptionWhenMoreThan100UsersAreRequested(array $ids, array $logins): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You can only request a total amount of 100 users at once');

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

        $usersApi = new UsersApi($apiClient);
        $usersApi->getUsers(ids: $ids, logins: $logins);
    }

    public function testUpdateUser(): void
    {
        $mockUser = $this->users[0];

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

    public function testUpdateUserThrowsExceptionWhenDescriptionIsTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A description can not be longer than 300 characters');

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

        $usersApi = new UsersApi($apiClient);
        $accessToken = new AccessToken(
            ['access_token' => 'doesn\'t matter, will throw an exception beforehand', 'token_type' => 'bearer']
        );

        $usersApi->updateUser(
            $accessToken,
            'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum'
        );
    }

    public function testBlockUser(): void
    {
        // There won't be any response object to check. The test is considered successful, when no exception is thrown
        $this->expectNotToPerformAssertions();

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

        $usersApi = new UsersApi($apiClient);
        $usersApi->blockUser(
            $this->users[1]['id'],
            new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:manage:blocked_users']))
        );
    }

    public function testGetUsersBlockList(): void
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

        $usersApi = new UsersApi($apiClient);
        $accessToken = new AccessToken(
            $this->getAccessTokenForUser($this->users[0]['id'], ['user:read:blocked_users'])
        );
        $userBlockListResponse = $usersApi->getUserBlockList($this->users[0]['id'], $accessToken);

        $this->assertInstanceOf(TwitchPaginatedDataResponse::class, $userBlockListResponse);
        $this->assertGreaterThan(0, count($userBlockListResponse->getData()));

        foreach ($userBlockListResponse->getData() as $block) {
            $this->assertIsString($block->getUserId());
            $this->assertIsString($block->getUserLogin());
            $this->assertIsString($block->getDisplayName());
        }

        // Because we are using the mock-api, these values are not set (yet).
        // $this->assertInstanceOf(Pagination::class, $userBlockListResponse->getPagination());
        // $this->assertIsString($userBlockListResponse->getPagination()->getCursor());
    }

    public function testUnblockUser(): void
    {
        // There won't be any response object to check (yet). The test is considered successful, when no exception is thrown
        $this->expectNotToPerformAssertions();

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

        $usersApi = new UsersApi($apiClient);
        $accessToken = new AccessToken(
            $this->getAccessTokenForUser($this->users[0]['id'], ['user:manage:blocked_users'])
        );
        $usersApi->unblockUser($this->users[1]['id'], $accessToken);
    }

    public function testGetUserExtensions(): void
    {
        $this->markTestSkipped('"/users/extensions/list" endpoint does not exist on mock-api');

        //        $client = new Client();
        //
        //        $requestFactory = new Psr17Factory();
        //        $apiClient = new ApiClient(
        //            $client,
        //            $requestFactory,
        //            new MapperBuilder(),
        //            $requestFactory,
        //            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        //        );
        //        $apiClient->setBaseUrl('http://localhost:8000/mock/');
        //
        //        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        //        $usersApi = new UsersApi($apiClient);
        //        $usersExtensionResponse = $usersApi->getUserExtensions($accessToken);
        //
        //        $this->assertInstanceOf(TwitchDataResponse::class, $usersExtensionResponse);
        //        $this->assertCount(1, $usersExtensionResponse->getData());
        //
        //        foreach ($usersExtensionResponse->getData() as $extension) {
        //            $this->assertInstanceOf(UserExtension::class, $extension);
        //            $this->assertTrue($extension->canActivate());
        //            $this->assertEquals('123456', $extension->getId());
        //        }
    }

    public function testGetUserActiveExtensionsWithUserToken(): void
    {
        $this->markTestSkipped('"/users/extensions" endpoint does not exist on mock-api');

        //        $client = new Client();
        //
        //        $requestFactory = new Psr17Factory();
        //        $apiClient = new ApiClient(
        //            $client,
        //            $requestFactory,
        //            new MapperBuilder(),
        //            $requestFactory,
        //            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        //        );
        //        $apiClient->setBaseUrl('http://localhost:8000/mock/');
        //
        //        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        //        $usersApi = new UsersApi($apiClient);
        //        $usersGetActiveExtensions = $usersApi->getUserActiveExtensions(accessToken: $accessToken);
        //
        //        $this->assertInstanceOf(TwitchDataResponse::class, $usersGetActiveExtensions);
        //        $this->assertIsNotArray($usersGetActiveExtensions->getData());
        //
        //        foreach ($usersGetActiveExtensions->getData() as $extension) {
        //            $this->assertInstanceOf(UserActiveExtension::class, $extension);
        //        }
    }

    public function testGetUserActiveExtensionsWithAppToken(): void
    {
        $this->markTestSkipped('"/users/extensions" endpoint does not exist on mock-api');

        //        $client = new Client();
        //
        //        $requestFactory = new Psr17Factory();
        //        $apiClient = new ApiClient(
        //            $client,
        //            $requestFactory,
        //            new MapperBuilder(),
        //            $requestFactory,
        //            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        //        );
        //        $apiClient->setBaseUrl('http://localhost:8000/mock/');
        //
        //        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        //        $usersApi = new UsersApi($apiClient);
        //        $usersGetActiveExtensions = $usersApi->getUserActiveExtensions('123456789', accessToken: $accessToken);
        //
        //        $this->assertInstanceOf(TwitchDataResponse::class, $usersGetActiveExtensions);
        //        $this->assertIsNotArray($usersGetActiveExtensions->getData());
        //
        //        foreach ($usersGetActiveExtensions->getData() as $extension) {
        //            $this->assertInstanceOf(UserActiveExtension::class, $extension);
        //        }
    }

    public function testUpdateUserExtensions(): void
    {
        // @TODO: Create an extension and validate this test
        $this->markTestSkipped('This test "might" be correct, but I can\'t actually validate the response.');

        //        $updateUserExtensionsBody = new UpdateUserExtension(
        //            json_decode(
        //                <<<'JSON'
        //{"data":{"panel":{"1":{"active":true,"id":"123","version":"1.2.5","name":"Some Extension"}}}}
        //JSON
        //                ,
        //                true
        //            )
        //        );
        //
        //        $client = new Client();
        //
        //        $requestFactory = new Psr17Factory();
        //        $apiClient = new ApiClient(
        //            $client,
        //            $requestFactory,
        //            new MapperBuilder(),
        //            $requestFactory,
        //            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        //        );
        //        $apiClient->setBaseUrl('http://localhost:8000/mock/');
        //
        //        $accessToken = new AccessToken($this->getAccessTokenForUser($this->users[0]['id'], ['user:edit:broadcast']));
        //        $usersApi = new UsersApi($apiClient);
        //        $updateUserExtensionsResponse = $usersApi->updateUserExtensions($updateUserExtensionsBody, $accessToken);
        //
        //        $this->assertInstanceOf(TwitchDataResponse::class, $updateUserExtensionsResponse);
        //
        //        $data = $updateUserExtensionsResponse->getData();
        //
        //        $this->assertInstanceOf(UserActiveExtension::class, $data);
        //        $this->assertIsArray($data->getPanel());
        //
        //        foreach ($data->getPanel() as $panel) {
        //            $this->assertInstanceOf(Panel::class, $panel);
        //        }
        //
        //        $this->assertIsArray($data->getOverlay());
        //
        //        foreach ($data->getOverlay() as $overlay) {
        //            $this->assertInstanceOf(Overlay::class, $overlay);
        //        }
        //
        //        $this->assertIsArray($data->getComponent());
        //
        //        foreach ($data->getComponent() as $component) {
        //            $this->assertInstanceOf(Component::class, $component);
        //        }
    }
}
