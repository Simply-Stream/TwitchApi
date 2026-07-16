<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelBitsUseCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\Bits\CustomPowerUp;
use SimplyStream\TwitchApi\EventSub\Events\Bits\PowerUp;
use SimplyStream\TwitchApi\EventSub\Shared\Message;

#[EventSubSubscription(type: 'channel.bits.use', version: '1', condition: ChannelBitsUseCondition::class)]
final readonly class ChannelBitsUseEvent implements EventInterface
{
    /**
     * @param string             $broadcasterUserId    The User ID of the channel where the Bits were redeemed.
     * @param string             $broadcasterUserLogin The login of the channel where the Bits were used.
     * @param string             $broadcasterUserName  The display name of the channel where the Bits were used.
     * @param string             $userId               The User ID of the redeeming user.
     * @param string             $userLogin            The login name of the redeeming user.
     * @param string             $userName             The display name of the redeeming user.
     * @param int                $bits                 The number of Bits used.
     * @param string             $type                 Possible values are: cheer, power_up, custom_power_up.
     * @param Message|null       $message              Optional. An object that contains the user message and emote
     *                                                 information needed to recreate the message.
     * @param PowerUp|null       $powerUp              Optional. Data about a default (i.e. built-in) Power-up.
     * @param CustomPowerUp|null $customPowerUp        Optional. Data about a custom Power-up.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public int $bits,
        public string $type,
        public ?Message $message = null,
        public ?PowerUp $powerUp = null,
        public ?CustomPowerUp $customPowerUp = null,
    ) {
    }
}
