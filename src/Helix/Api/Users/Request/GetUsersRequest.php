<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

use Webmozart\Assert\Assert;

final readonly class GetUsersRequest
{
    /**
     * @param list<string> $ids    The ID of the user to get. To specify more than one user, include the id parameter
     *                            for each user to get. For example, id=1234&id=5678. The maximum number of IDs you may
     *                            specify is 100.
     * @param list<string> $logins The login name of the user to get. To specify more than one user, include the login
     *                            parameter for each user to get. For example, login=foo&login=bar. The maximum number
     *                            of login names you may specify is 100.
     */
    public function __construct(
        public array $ids = [],
        public array $logins = [],
    ) {
        Assert::allString($ids);
        Assert::allString($logins);

        $total = count($ids) + count($logins);

        Assert::greaterThan($total, 0, 'You need to specify at least one id or login.');
        Assert::lessThanEq($total, 100, 'You can only request a total amount of 100 users at once.');
    }
}
