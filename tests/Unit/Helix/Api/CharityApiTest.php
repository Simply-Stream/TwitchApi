<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Charity\Request\GetCharityCampaignDonationsRequest;
use SimplyStream\TwitchApi\Helix\Api\Charity\Request\GetCharityCampaignRequest;
use SimplyStream\TwitchApi\Helix\Api\Charity\Response\CharityCampaignDonationsResponse;
use SimplyStream\TwitchApi\Helix\Api\Charity\Response\CharityCampaignResponse;
use SimplyStream\TwitchApi\Helix\Api\CharityApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(CharityApi::class)]
final class CharityApiTest extends TestCase
{
    private ApiClientInterface $apiClient;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;
    private StaticAccessToken $token;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->token = new StaticAccessToken();
    }

    private function api(): CharityApi
    {
        return new CharityApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_charity_campaign_forwards_broadcaster_id(): void
    {
        $raw = ['data' => []];
        $expected = new CharityCampaignResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'charity/campaigns', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CharityCampaignResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getCharityCampaign(new GetCharityCampaignRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_charity_campaign_donations_omits_null_and_hits_donations_path(): void
    {
        $raw = ['data' => []];
        $expected = new CharityCampaignDonationsResponse(data: []);

        // after defaults to null -> filtered; broadcasterId and first remain.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'charity/donations', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CharityCampaignDonationsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getCharityCampaignDonations(
                new GetCharityCampaignDonationsRequest(broadcasterId: '1234'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_charity_campaign_donations_forwards_pagination_cursor(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'charity/donations', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 50,
                'after'          => 'cursor-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new CharityCampaignDonationsResponse(data: []));

        $this->api()->getCharityCampaignDonations(
            new GetCharityCampaignDonationsRequest(broadcasterId: '1234', first: 50, after: 'cursor-1'),
            $this->token,
        );
    }
}
