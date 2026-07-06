<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Bits\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Bits\ExtensionTransactions;

final readonly class ExtensionTransactionsResponse
{
    /** @param list<ExtensionTransactions> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}
