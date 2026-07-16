<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use Webmozart\Assert\Assert;

final readonly class SendChatAnnouncement
{
    private const array VALID_COLORS = ['blue', 'green', 'orange', 'purple', 'primary'];

    /**
     * @param string $message The announcement to make in the broadcaster’s chat room. Announcements are limited to a
     *                        maximum of 500 characters; announcements longer than 500 characters are truncated.
     * @param string $color   The color used to highlight the announcement. Possible case-sensitive values are:
     *                        - blue
     *                        - green
     *                        - orange
     *                        - purple
     *                        - primary (default)
     *                        If color is set to primary or is not set, the channel’s accent color is used to highlight
     *                        the announcement.
     */
    public function __construct(
        public string $message,
        public string $color = 'primary',
    ) {
        Assert::maxLength(
            $this->message,
            500,
            sprintf('Messages can only be %d characters long. Got "%d" characters', 500, strlen($this->message)),
        );
        Assert::inArray(
            $this->color,
            self::VALID_COLORS,
            'Color can only be one of the following values: blue, green, orange, purple, primary. Got %s',
        );
    }
}
