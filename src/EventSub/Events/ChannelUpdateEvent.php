<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelUpdateCondition;

#[EventSubSubscription(type: 'channel.update', version: '1', condition: ChannelUpdateCondition::class)]
final readonly class ChannelUpdateEvent
{
    /**
     * @param string $broadcasterUserId           The broadcaster’s user ID.
     * @param string $broadcasterUserLogin        The broadcaster’s user login.
     * @param string $broadcasterUserName         The broadcaster’s user display name.
     * @param string $title                       The channel’s stream title.
     * @param string $language                    The channel’s broadcast language.
     * @param string $categoryId                  The channel’s category ID.
     * @param string $categoryName                The category name.
     * @param array  $contentClassificationLabels Array of content classification label IDs currently applied on the
     *                                            Channel. To retrieve a list of all possible IDs, use the Get Content
     *                                            Classification Labels API endpoint.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $title,
        public string $language,
        public string $categoryId,
        public string $categoryName,
        public array $contentClassificationLabels = []
    ) {
    }
}
