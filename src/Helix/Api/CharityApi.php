<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use CuyZ\Valinor\Mapper\MappingError;
use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaign;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaignDonation;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchResponseInterface;

class CharityApi extends AbstractApi
{
    protected const BASE_PATH = 'charity';

    /**
     * Gets information about the charity campaign that a broadcaster is running. For example, the campaign’s
     * fundraising goal and the current amount of donations.
     *
     * To receive events when progress is made towards the campaign’s goal or the broadcaster changes the fundraising
     * goal, subscribe to the channel.charity_campaign.progress subscription type.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:charity scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/charity/campaigns
     *
     * @param string               $broadcasterId The ID of the broadcaster that’s currently running a charity
     *                                            campaign. This ID must match the user ID in the access token.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the channel:read:charity
     *                                            scope.
     *
     * @return TwitchDataResponse<CharityCampaign[]>
     */
    public function getCharityCampaign(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/campaigns',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, CharityCampaign::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets the list of donations that users have made to the broadcaster’s active charity campaign.
     *
     * To receive events as donations occur, subscribe to the channel.charity_campaign.donate subscription type.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:charity scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/charity/donations
     *
     * @param string               $broadcasterId The ID of the broadcaster that’s currently running a charity
     *                                            campaign. This ID must match the user ID in the access token.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the channel:read:charity
     *                                            scope.
     * @param int                  $first         The maximum number of items to return per page in the response. The
     *                                            minimum page size is
     *                                            1 item per page and the maximum is 100. The default is 20.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<CharityCampaignDonation[]>
     */
    public function getCharityCampaignDonations(
        string $broadcasterId,
        AccessTokenInterface $accessToken,
        int $first = 20,
        string $after = null
    ): TwitchResponseInterface {
        return $this->sendRequest(
            path: self::BASE_PATH . '/donations',
            query: [
                'broadcaster_id' => $broadcasterId,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, CharityCampaignDonation::class),
            accessToken: $accessToken
        );
    }
}
