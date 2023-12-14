<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\CharityApi;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaignDonation;
use SimplyStream\TwitchApi\Helix\Models\Pagination;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class CharityApiTest extends UserAwareFunctionalTestCase
{
    public function testGetCharityCampaign()
    {
        $this->markTestSkipped('Waiting for https://github.com/twitchdev/twitch-cli/pull/300 to be approved');

        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $charityApi = new CharityApi($apiClient);
        $charityCampaignResponse = $charityApi->getCharityCampaign(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:read:charity']))
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $charityCampaignResponse);
        $this->assertIsArray($charityCampaignResponse->getData());
        $this->assertGreaterThan(0, count($charityCampaignResponse->getData()));

        foreach ($charityCampaignResponse->getData() as $charityCampaign) {
            $this->assertIsString($charityCampaign->getId());
            $this->assertNotEmpty($charityCampaign->getId());
            $this->assertSame($testUser['id'], $charityCampaign->getBroadcasterId());
            $this->assertSame($testUser['login'], $charityCampaign->getBroadcasterLogin());
            $this->assertSame($testUser['display_name'], $charityCampaign->getBroadcasterName());
            $this->assertIsString($charityCampaign->getCharityName());
            $this->assertNotEmpty($charityCampaign->getCharityName());
            $this->assertIsString($charityCampaign->getCharityDescription());
            $this->assertNotEmpty($charityCampaign->getCharityDescription());
            $this->assertIsString($charityCampaign->getCharityLogo());
            $this->assertIsString($charityCampaign->getCharityLogo());
            $this->assertNotEmpty($charityCampaign->getCharityWebsite());
            $this->assertNotEmpty($charityCampaign->getCharityWebsite());

            $this->assertInstanceOf(CharityAmount::class, $charityCampaign->getCurrentAmount());
            $this->assertGreaterThan(0, $charityCampaign->getCurrentAmount()->getValue());
            $this->assertSame(2, $charityCampaign->getCurrentAmount()->getDecimalPlaces());
            $this->assertSame("USD", $charityCampaign->getCurrentAmount()->getCurrency());
            $this->assertInstanceOf(CharityAmount::class, $charityCampaign->getTargetAmount());
            $this->assertGreaterThan(0, $charityCampaign->getTargetAmount()->getValue());
            $this->assertSame(2, $charityCampaign->getTargetAmount()->getDecimalPlaces());
            $this->assertSame("USD", $charityCampaign->getTargetAmount()->getCurrency());
        }
    }

    public function testGetCharityCampaignDonations()
    {
        $this->markTestSkipped('Waiting for https://github.com/twitchdev/twitch-cli/pull/300 to be approved');

        $testUser = $this->users[0];
        $client = new Client();

        $requestFactory = new Psr17Factory();
        $apiClient = new ApiClient(
            $client,
            $requestFactory,
            new MapperBuilder(),
            $requestFactory,
            ['clientId' => $this->clients['ID'], 'webhook' => ['secret' => '1234567890']]
        );
        $apiClient->setBaseUrl('http://localhost:8000/mock/');

        $charityApi = new CharityApi($apiClient);
        $charityCampaignDonationsResponse = $charityApi->getCharityCampaignDonations(
            $testUser['id'],
            new AccessToken($this->getAccessTokenForUser($testUser['id'], ['channel:read:charity']))
        );

        $this::assertInstanceOf(TwitchPaginatedDataResponse::class, $charityCampaignDonationsResponse);
        $this->assertGreaterThan(0, count($charityCampaignDonationsResponse->getData()));
        $this->assertContainsOnlyInstancesOf(
            CharityCampaignDonation::class,
            $charityCampaignDonationsResponse->getData()
        );

        foreach ($charityCampaignDonationsResponse->getData() as $charityCampaignDonation) {
            $this->assertIsString($charityCampaignDonation->getId());
            $this->assertNotEmpty($charityCampaignDonation->getId());
            $this->assertIsString($charityCampaignDonation->getCampaignId());
            $this->assertNotEmpty($charityCampaignDonation->getCampaignId());
            $this->assertIsString($charityCampaignDonation->getUserId());
            $this->assertNotEmpty($charityCampaignDonation->getUserId());
            $this->assertIsString($charityCampaignDonation->getUserLogin());
            $this->assertNotEmpty($charityCampaignDonation->getUserLogin());
            $this->assertIsString($charityCampaignDonation->getUserName());
            $this->assertNotEmpty($charityCampaignDonation->getUserName());
        }

        $this->assertInstanceOf(Pagination::class, $charityCampaignDonationsResponse->getPagination());
    }
}
