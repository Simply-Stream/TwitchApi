<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Bits;

use DateTimeInterface;

final readonly class ExtensionTransactions
{
    /**
     * @param string            $id               An ID that identifies the transaction.
     * @param DateTimeInterface $timestamp        The UTC date and time (in RFC3339 format) of the transaction.
     * @param string            $broadcasterId    The ID of the broadcaster that owns the channel where the transaction
     *                                            occurred.
     * @param string            $broadcasterLogin The broadcaster’s login name.
     * @param string            $broadcasterName  The broadcaster’s display name.
     * @param string            $userId           The ID of the user that purchased the digital product.
     * @param string            $userLogin        The user’s login name.
     * @param string            $userName         The user’s display name.
     * @param string            $productType      The type of transaction. Possible values are:
     *                                            - BITS_IN_EXTENSION
     * @param ProductData       $productData      Contains details about the digital product.
     */
    public function __construct(
        public string $id,
        public DateTimeInterface $timestamp,
        public string $broadcasterId,
        public string $broadcasterLogin,
        public string $broadcasterName,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $productType,
        public ProductData $productData,
    ) {
    }
}
