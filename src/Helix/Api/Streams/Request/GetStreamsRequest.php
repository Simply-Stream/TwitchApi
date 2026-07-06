<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Request;

use SimplyStream\TwitchApi\Helix\Api\Streams\StreamType;
use Webmozart\Assert\Assert;

final readonly class GetStreamsRequest
{
    /**
     * @param list<string> $userIds    A user ID used to filter the list of streams. Returns only the streams of those
     *                                 users that are broadcasting. You may specify a maximum of 100 IDs. To specify
     *                                 multiple IDs, include the user_id parameter for each user. For example,
     *                                 &user_id=1234&user_id=5678.
     * @param list<string> $userLogins A user login name used to filter the list of streams. Returns only the streams of
     *                                 those users that are broadcasting. You may specify a maximum of 100 login names.
     *                                 To specify multiple names, include the user_login parameter for each user. For
     *                                 example, &user_login=foo&user_login=bar.
     * @param list<string> $gameIds    A game (category) ID used to filter the list of streams. Returns only the streams
     *                                 that are broadcasting the game (category). You may specify a maximum of 100 IDs.
     *                                 To specify multiple IDs, include the game_id parameter for each game. For example,
     *                                 &game_id=9876&game_id=5432.
     * @param StreamType   $type       The type of stream to filter the list of streams by. The default is all.
     * @param list<string> $languages  A language code used to filter the list of streams. Returns only streams that
     *                                 broadcast in the specified language. Specify the language using an ISO 639-1
     *                                 two-letter language code or other if the broadcast uses a language not in the list
     *                                 of supported stream languages.
     *
     *                                 You may specify a maximum of 100 language codes. To specify multiple languages,
     *                                 include the language parameter for each language. For example, &language=de&language=fr.
     * @param int          $first      The maximum number of items to return per page in the response. The minimum page
     *                                 size is 1 item per page and the maximum is 100 items per page. The default is 20.
     * @param string|null  $before     The cursor used to get the previous page of results. The Pagination object in the
     *                                 response contains the cursor’s value.
     * @param string|null  $after      The cursor used to get the next page of results. The Pagination object in the
     *                                 response contains the cursor’s value.
     */
    public function __construct(
        public array $userIds = [],
        public array $userLogins = [],
        public array $gameIds = [],
        public StreamType $type = StreamType::All,
        public array $languages = [],
        public int $first = 20,
        public ?string $before = null,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
        Assert::maxCount($userIds, 100);
        Assert::maxCount($userLogins, 100);
        Assert::maxCount($gameIds, 100);
        Assert::maxCount($languages, 100);
        Assert::allString($userIds);
        Assert::allString($userLogins);
        Assert::allString($gameIds);
        Assert::allString($languages);
    }
}
