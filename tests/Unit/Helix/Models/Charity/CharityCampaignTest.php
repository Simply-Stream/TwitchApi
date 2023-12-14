<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Charity;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaign;

class CharityCampaignTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $charityAmount = new CharityAmount(1000, 2, 'USD');
        $charityCampaign = new CharityCampaign(
            'id',
            'broadcasterId',
            'broadcasterLogin',
            'broadcasterName',
            'charityName',
            'charityDescription',
            'charityLogo',
            'charityWebsite',
            $charityAmount,
            $charityAmount
        );

        $expectedArray = [
            'id' => 'id',
            'broadcaster_id' => 'broadcasterId',
            'broadcaster_login' => 'broadcasterLogin',
            'broadcaster_name' => 'broadcasterName',
            'charity_name' => 'charityName',
            'charity_description' => 'charityDescription',
            'charity_logo' => 'charityLogo',
            'charity_website' => 'charityWebsite',
            'current_amount' => $charityAmount->toArray(),
            'target_amount' => $charityAmount->toArray(),
        ];

        $this->assertInstanceOf(CharityCampaign::class, $charityCampaign);
        $this->assertSame('id', $charityCampaign->getId());
        $this->assertSame('broadcasterId', $charityCampaign->getBroadcasterId());
        $this->assertSame('broadcasterLogin', $charityCampaign->getBroadcasterLogin());
        $this->assertSame('broadcasterName', $charityCampaign->getBroadcasterName());
        $this->assertSame('charityName', $charityCampaign->getCharityName());
        $this->assertSame('charityDescription', $charityCampaign->getCharityDescription());
        $this->assertSame('charityLogo', $charityCampaign->getCharityLogo());
        $this->assertSame('charityWebsite', $charityCampaign->getCharityWebsite());
        $this->assertSame($charityAmount, $charityCampaign->getCurrentAmount());
        $this->assertSame($charityAmount, $charityCampaign->getTargetAmount());
        $this->assertSame($expectedArray, $charityCampaign->toArray());
    }
}
