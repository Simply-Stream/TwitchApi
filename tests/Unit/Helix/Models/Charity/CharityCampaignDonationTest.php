<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Charity;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaignDonation;

final class CharityCampaignDonationTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $charityAmount = new CharityAmount(10, 2, 'USD');

        $charityCampaignDonation = new CharityCampaignDonation(
            'id',
            'campaignId',
            'userId',
            'userLogin',
            'userName',
            $charityAmount
        );

        $this->assertInstanceOf(CharityCampaignDonation::class, $charityCampaignDonation);
        $this->assertEquals('id', $charityCampaignDonation->getId());
        $this->assertEquals('campaignId', $charityCampaignDonation->getCampaignId());
        $this->assertEquals('userId', $charityCampaignDonation->getUserId());
        $this->assertEquals('userLogin', $charityCampaignDonation->getUserLogin());
        $this->assertEquals('userName', $charityCampaignDonation->getUserName());
        $this->assertEquals($charityAmount, $charityCampaignDonation->getAmount());

        $expectedArray = [
            'id' => 'id',
            'campaign_id' => 'campaignId',
            'user_id' => 'userId',
            'user_login' => 'userLogin',
            'user_name' => 'userName',
            'amount' => [
                'value' => 10,
                'decimal_places' => 2,
                'currency' => 'USD',
            ],
        ];

        $this->assertEquals($expectedArray, $charityCampaignDonation->toArray());
    }
}
