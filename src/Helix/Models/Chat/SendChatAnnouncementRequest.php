<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class SendChatAnnouncementRequest extends AbstractModel
{
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
     *                        the announcement
     *                        (see Profile Accent Color under profile settings, Channel and Videos, and Brand).
     */
    public function __construct(
        private string $message,
        private string $color = 'primary'
    ) {
        Assert::maxLength(
            $this->message,
            500,
            sprintf('Messages can only be %2$s characters long. Got "%s" characters', strlen($this->message), 500)
        );
        Assert::inArray(
            $this->color,
            ['blue', 'green', 'orange', 'purple', 'primary'],
            'Color can only be one of the following values: blue, green, orange, purple, primary. Got %s',
        );
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
}
