<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotification;

final readonly class Badge
{
    /**
     * @param string $setId An ID that identifies this set of chat badges. For example, Bits or Subscriber.
     * @param string $id    An ID that identifies this version of the badge. The ID can be any value. For example, for
     *                      Bits, the ID is the Bits tier level, but for World of Warcraft, it could be Alliance or
     *                      Horde.
     * @param string $info  Contains metadata related to the chat badges in the badges tag. Currently, this tag
     *                      contains metadata only for subscriber badges, to indicate the number of months the user has
     *                      been a subscriber.
     */
    public function __construct(
        public string $setId,
        public string $id,
        public string $info
    ) {
    }
}
