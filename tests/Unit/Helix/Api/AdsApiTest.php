<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\AdsApi;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Models\Ads\Commercial;
use SimplyStream\TwitchApi\Helix\Models\Ads\StartCommercialRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class AdsApiTest extends TestCase
{
    protected AdsApi $ads;

    protected function setUp(): void
    {
        parent::setUp();

        $commercials = [
            new Commercial(180, '', 480),
        ];

        $twitchResponse = new TwitchDataResponse($commercials);

        $apiClientMock = $this->createMock(ApiClient::class);
        $apiClientMock
            ->method('sendRequest')
            ->willReturn($twitchResponse);

        $this->ads = new AdsApi($apiClientMock);
    }

    public function testStartCommercial(): void
    {
        $startCommercialRequest = new StartCommercialRequest('testId', 30);
        $accessToken = $this->createStub(AccessTokenInterface::class);

        $result = $this->ads->startCommercial($startCommercialRequest, $accessToken);

        $this->assertInstanceOf(TwitchDataResponse::class, $result);
        $this->assertIsArray($result->getData());
        $this->assertNotEmpty($result->getData());

        foreach ($result->getData() as $commercial) {
            $this->assertInstanceOf(Commercial::class, $commercial);
        }
    }
}
