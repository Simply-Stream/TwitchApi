<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetBitsLeaderboardRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetCheermotesRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetExtensionTransactionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\BitsLeaderboardResponse;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\CheermotesResponse;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\ExtensionTransactionsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class BitsApi extends AbstractApi
{
    private const string BASE_PATH = 'bits';

    /**
     * Gets the Bits leaderboard for the authenticated broadcaster.
     *
     * Authorization
     * Requires a user access token that includes the bits:read scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/bits/leaderboard
     *
     * @param GetBitsLeaderboardRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the bits:read scope.
     *
     * @return BitsLeaderboardResponse
     */
    public function getBitsLeaderboard(
        GetBitsLeaderboardRequest $request,
        AccessTokenInterface $accessToken,
    ): BitsLeaderboardResponse {
        $query = array_filter(
            [
                'count'      => $request->count,
                'period'     => $request->period->value,
                'started_at' => $request->startedAt?->format(DATE_RFC3339_EXTENDED),
                'user_id'    => $request->userId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/leaderboard',
            BitsLeaderboardResponse::class,
            $accessToken,
            $query,
        );
    }

    /**
     * Gets a list of Cheermotes that users can use to cheer Bits in any Bits-enabled channel’s chat room. Cheermotes
     * are animated emotes that viewers can assign Bits to.
     *
     * URL
     * GET https://api.twitch.tv/helix/bits/cheermotes
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * @param GetCheermotesRequest $request
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     *
     * @return CheermotesResponse
     */
    public function getCheermotes(
        GetCheermotesRequest $request,
        AccessTokenInterface $accessToken,
    ): CheermotesResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/cheermotes',
            CheermotesResponse::class,
            $accessToken,
            $query,
        );
    }

    /**
     * Gets an extension’s list of transactions. A transaction records the exchange of a currency (for example, Bits)
     * for a digital product.
     *
     * Authorization
     * Requires an app access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/transactions
     *
     * @param GetExtensionTransactionsRequest $request
     * @param AccessTokenInterface            $accessToken Requires an app access token.
     *
     * @return ExtensionTransactionsResponse
     */
    public function getExtensionTransactions(
        GetExtensionTransactionsRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionTransactionsResponse {
        $query = array_filter(
            [
                'extension_id' => $request->extensionId,
                'id'           => $request->ids,
                'first'        => $request->first,
                'after'        => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(
            'extensions/transactions',
            ExtensionTransactionsResponse::class,
            $accessToken,
            $query,
        );
    }
}
