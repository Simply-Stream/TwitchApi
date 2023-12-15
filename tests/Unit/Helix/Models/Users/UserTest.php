<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\User;

final class UserTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = 'uniqueId';
        $login = 'userLogin';
        $displayName = 'DisplayName';
        $type = 'admin';
        $broadcasterType = 'partner';
        $description = 'profile description';
        $profileImageUrl = 'https://example.com/image.jpg';
        $offlineImageUrl = 'https://example.com/offline.jpg';
        $viewCount = 1;
        $createdAt = new DateTimeImmutable();
        $email = 'user@example.com';

        $user = new User(
            $id,
            $login,
            $displayName,
            $type,
            $broadcasterType,
            $description,
            $profileImageUrl,
            $offlineImageUrl,
            $viewCount,
            $createdAt,
            $email
        );

        $this->assertSame($id, $user->getId(), "User ID doesn't match!");
        $this->assertSame($login, $user->getLogin(), "Login name doesn't match!");
        $this->assertSame($displayName, $user->getDisplayName(), "Display name doesn't match!");
        $this->assertSame($type, $user->getType(), "User type doesn't match!");
        $this->assertSame($broadcasterType, $user->getBroadcasterType(), "Broadcaster type doesn't match!");
        $this->assertSame($description, $user->getDescription(), "Description doesn't match!");
        $this->assertSame($profileImageUrl, $user->getProfileImageUrl(), "Profile image URL doesn't match!");
        $this->assertSame($offlineImageUrl, $user->getOfflineImageUrl(), "Offline image URL doesn't match!");
        $this->assertSame($viewCount, $user->getViewCount(), "View count doesn't match!");
        $this->assertSame($email, $user->getEmail(), "Email doesn't match!");
        $this->assertSame($createdAt, $user->getCreatedAt(), "Created at timestamp doesn't match!");
    }
}
