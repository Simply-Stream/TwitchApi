<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ExtensionBitsTransactionCreateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'extension.bits_transaction.create', version: '1', condition: ExtensionBitsTransactionCreateCondition::class)]
final readonly class ExtensionBitsTransactionCreateEvent implements EventInterface
{
    /**
     * @param string  $extensionClientId    Client ID of the extension.
     * @param string  $id                   Transaction ID.
     * @param string  $broadcasterUserId    The transaction’s broadcaster ID.
     * @param string  $broadcasterUserLogin The transaction’s broadcaster login.
     * @param string  $broadcasterUserName  The transaction’s broadcaster display name.
     * @param string  $userId               The transaction’s user ID.
     * @param string  $userLogin            The transaction’s user login.
     * @param string  $userName             The transaction’s user display name.
     * @param Product $product              Additional extension product information.
     */
    public function __construct(
        public string $extensionClientId,
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public Product $product
    ) {
    }
}
