<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Charity\Request\GetCharityCampaignDonationsRequest;
use SimplyStream\TwitchApi\Helix\Api\Charity\Request\GetCharityCampaignRequest;
use SimplyStream\TwitchApi\Helix\Api\Charity\Response\CharityCampaignDonationsResponse;
use SimplyStream\TwitchApi\Helix\Api\Charity\Response\CharityCampaignResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class CharityApi extends AbstractApi
{
    private const string BASE_PATH = 'charity';

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
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the channel:read:charity
     *                                               scope.
     */
    public function getCharityCampaign(
        GetCharityCampaignRequest $request,
        AccessTokenInterface $accessToken,
    ): CharityCampaignResponse {
        return $this->get(
            self::BASE_PATH . '/campaigns',
            CharityCampaignResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
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
     * @param AccessTokenInterface               $accessToken Requires a user access token that includes the
     *                                                        channel:read:charity scope.
     */
    public function getCharityCampaignDonations(
        GetCharityCampaignDonationsRequest $request,
        AccessTokenInterface $accessToken,
    ): CharityCampaignDonationsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/donations',
            CharityCampaignDonationsResponse::class,
            $accessToken,
            $query,
        );
    }
}
