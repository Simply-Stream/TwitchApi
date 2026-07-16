<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

use DateTimeInterface;

final readonly class User
{
    /**
     * @param string            $id              An ID that identifies the user.
     * @param string            $login           The user’s login name.
     * @param string            $displayName     The user’s display name.
     * @param string            $type            The type of user. Possible values are:
     *                                           - admin — Twitch administrator
     *                                           - global_mod
     *                                           - staff — Twitch staff
     *                                           - "" — Normal user
     * @param string            $broadcasterType The type of broadcaster. Possible values are:
     *                                           - affiliate — An affiliate broadcaster
     *                                           - partner — A partner broadcaster
     *                                           - "" — A normal broadcaster
     * @param string            $description     The user’s description of their channel.
     * @param string            $profileImageUrl A URL to the user’s profile image.
     * @param string            $offlineImageUrl A URL to the user’s offline image.
     * @param int               $viewCount       The number of times the user’s channel has been viewed.
     *
     *                                           NOTE: This field has been deprecated. Any data in this field is not
     *                                           valid and should not be used.
     * @param DateTimeInterface $createdAt       The UTC date and time that the user’s account was created.
     * @param string|null       $email           The user’s verified email address. Included only if the user access
     *                                           token includes the user:read:email scope.
     *
     *                                           If the request contains more than one user, only the user associated
     *                                           with the access token that provided consent will include an email
     *                                           address — the other users’ email will be empty or absent.
     */
    public function __construct(
        public string $id,
        public string $login,
        public string $displayName,
        public string $type,
        public string $broadcasterType,
        public string $description,
        public string $profileImageUrl,
        public string $offlineImageUrl,
        public int $viewCount,
        public DateTimeInterface $createdAt,
        public ?string $email = null,
    ) {
    }
}
