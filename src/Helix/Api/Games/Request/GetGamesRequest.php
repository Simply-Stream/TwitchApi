<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Games\Request;

use Webmozart\Assert\Assert;

final readonly class GetGamesRequest
{
    /**
     * @param list<string> $ids     The ID of the category or game to get. Include this parameter for each category or
     *                             game you want to get. For example, &id=1234&id=5678. You may specify a maximum of 100
     *                             IDs. The endpoint ignores duplicate and invalid IDs or IDs that weren’t found.
     * @param list<string> $names   The name of the category or game to get. The name must exactly match the category’s
     *                             or game’s title. Include this parameter for each category or game you want to get.
     *                             For example, &name=foo&name=bar. You may specify a maximum of 100 names. The endpoint
     *                             ignores duplicate names and names that weren’t found.
     * @param list<string> $igdbIds The IGDB ID of the game to get. Include this parameter for each game you want to
     *                             get. For example, &igdb_id=1234&igdb_id=5678. You may specify a maximum of 100 IDs.
     *                             The endpoint ignores duplicate and invalid IDs or IDs that weren’t found.
     */
    public function __construct(
        public array $ids = [],
        public array $names = [],
        public array $igdbIds = [],
    ) {
        Assert::allString($ids);
        Assert::allString($names);
        Assert::allString($igdbIds);

        $total = count($ids) + count($names) + count($igdbIds);

        // Twitch caps the combined number of ids, names and IGDB ids at 100.
        Assert::lessThanEq($total, 100, 'You may specify a maximum of 100 ids, names and IGDB ids combined.');
        Assert::greaterThanEq($total, 1, 'You need at least one id, name or IGDB id to request.');
    }
}
