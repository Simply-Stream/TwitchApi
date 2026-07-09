<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Charity\Request\GetCharityCampaignDonationsRequest;
use SimplyStream\TwitchApi\Helix\Api\Charity\Request\GetCharityCampaignRequest;
use SimplyStream\TwitchApi\Helix\Api\Charity\Response\CharityCampaignDonationsResponse;
use SimplyStream\TwitchApi\Helix\Api\Charity\Response\CharityCampaignResponse;
use SimplyStream\TwitchApi\Helix\Api\CharityApi;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaign;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaignDonation;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsCharityApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(CharityApi::class)]
final class CharityApiTest extends TestCase
{
    use BuildsCharityApi;

    #[Test]
    public function get_charity_campaign_denormalizes_both_amount_objects(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'                  => '123-abc-456-def',
                'broadcaster_id'      => '123456',
                'broadcaster_login'   => 'sunnysideup',
                'broadcaster_name'    => 'SunnySideUp',
                'charity_name'        => 'Example name',
                'charity_description' => 'Example description',
                'charity_logo'        => 'https://abc.cloudfront.net/ppgf/1000/100.png',
                'charity_website'     => 'https://www.example.com',
                'current_amount'      => [
                    'value'          => 86000,
                    'decimal_places' => 2,
                    'currency'       => 'USD',
                ],
                'target_amount'       => [
                    'value'          => 1500000,
                    'decimal_places' => 2,
                    'currency'       => 'USD',
                ],
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCharityCampaign(
            new GetCharityCampaignRequest(broadcasterId: '123456'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/charity/campaigns', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=123456', $request->getUri()->getQuery());

        $this->assertInstanceOf(CharityCampaignResponse::class, $response);
        $this->assertCount(1, $response->data);

        $campaign = $response->data[0];
        $this->assertInstanceOf(CharityCampaign::class, $campaign);
        $this->assertSame('123-abc-456-def', $campaign->id);
        $this->assertSame('Example name', $campaign->charityName);
        $this->assertSame('https://www.example.com', $campaign->charityWebsite);

        $this->assertInstanceOf(CharityAmount::class, $campaign->currentAmount);
        $this->assertSame(86000, $campaign->currentAmount->value);
        $this->assertSame(2, $campaign->currentAmount->decimalPlaces);
        $this->assertSame('USD', $campaign->currentAmount->currency);

        $this->assertInstanceOf(CharityAmount::class, $campaign->targetAmount);
        $this->assertSame(1500000, $campaign->targetAmount->value);
    }

    #[Test]
    public function get_charity_campaign_accepts_a_null_target_amount(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'                  => '123-abc-456-def',
                'broadcaster_id'      => '123456',
                'broadcaster_login'   => 'sunnysideup',
                'broadcaster_name'    => 'SunnySideUp',
                'charity_name'        => 'Example name',
                'charity_description' => 'Example description',
                'charity_logo'        => 'https://abc.cloudfront.net/ppgf/1000/100.png',
                'charity_website'     => 'https://www.example.com',
                'current_amount'      => [
                    'value'          => 86000,
                    'decimal_places' => 2,
                    'currency'       => 'USD',
                ],
                'target_amount'       => null,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCharityCampaign(
            new GetCharityCampaignRequest(broadcasterId: '123456'),
            new StaticAccessToken(),
        );

        // The broadcaster has not defined a fundraising goal.
        $this->assertNull($response->data[0]->targetAmount);
        $this->assertInstanceOf(CharityAmount::class, $response->data[0]->currentAmount);
    }

    #[Test]
    public function get_charity_campaign_donations_denormalizes_the_amount_object(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'          => 'a1b2c3-aabb-4455-d1e2f3',
                'campaign_id' => '123-abc-456-def',
                'user_id'     => '5678',
                'user_login'  => 'cool_user',
                'user_name'   => 'Cool_User',
                'amount'      => [
                    'value'          => 500,
                    'decimal_places' => 2,
                    'currency'       => 'USD',
                ],
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCharityCampaignDonations(
            new GetCharityCampaignDonationsRequest(broadcasterId: '123456'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/charity/donations', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=123456&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(CharityCampaignDonationsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $donation = $response->data[0];
        $this->assertInstanceOf(CharityCampaignDonation::class, $donation);
        $this->assertSame('a1b2c3-aabb-4455-d1e2f3', $donation->id);
        $this->assertSame('Cool_User', $donation->userName);

        $this->assertInstanceOf(CharityAmount::class, $donation->amount);
        $this->assertSame(500, $donation->amount->value);
        $this->assertSame('USD', $donation->amount->currency);
    }

    #[Test]
    public function get_charity_campaign_donations_forwards_the_pagination_cursor(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCharityCampaignDonations(
            new GetCharityCampaignDonationsRequest(broadcasterId: '123456', first: 50, after: 'cursor-1'),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=123456&first=50&after=cursor-1',
            $http->getLastRequest()->getUri()->getQuery(),
        );
        $this->assertNull($response->pagination);
    }
}
