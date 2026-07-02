<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotification;

final readonly class PayItForward
{
    /**
     * @param bool        $gifterIsAnonymous Whether the gift was given anonymously.
     * @param string|null $gifterUserId      Optional. The user ID of the user who gifted the subscription. Null if
     *                                       anonymous.
     * @param string|null $gifterUserName    Optional. The user name of the user who gifted the subscription. Null if
     *                                       anonymous.
     * @param string|null $gifterUserLogin   Optional. The user login of the user who gifted the subscription. Null if
     *                                       anonymous.
     */
    public function __construct(
        public bool $gifterIsAnonymous,
        public ?string $gifterUserId = null,
        public ?string $gifterUserName = null,
        public ?string $gifterUserLogin = null
    ) {
    }
}
