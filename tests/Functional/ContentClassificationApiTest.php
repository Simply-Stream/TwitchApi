<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Functional;

use CuyZ\Valinor\MapperBuilder;
use GuzzleHttp\Client;
use Http\Discovery\Psr17Factory;
use League\OAuth2\Client\Token\AccessToken;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\ContentClassificationApi;
use SimplyStream\TwitchApi\Helix\Models\CCLs\ContentClassificationLabel;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Tests\Helper\UserAwareFunctionalTestCase;

class ContentClassificationApiTest extends UserAwareFunctionalTestCase
{
    public function testGetContentClassificationLevels()
    {
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

        $contentClassificationApi = new ContentClassificationApi($apiClient);
        $contentClassificationLevelsResponse = $contentClassificationApi->getContentClassificationLevels(
            new AccessToken($this->appAccessToken)
        );

        $this->assertInstanceOf(TwitchDataResponse::class, $contentClassificationLevelsResponse);
        $this->assertCount(6, $contentClassificationLevelsResponse->getData());

        $this->assertContainsOnlyInstancesOf(
            ContentClassificationLabel::class,
            $contentClassificationLevelsResponse->getData()
        );

        foreach ($contentClassificationLevelsResponse->getData() as $contentClassificationLabel) {
            $this->assertIsString($contentClassificationLabel->getId());
            $this->assertNotEmpty($contentClassificationLabel->getId());
            $this->assertIsString($contentClassificationLabel->getDescription());
            $this->assertNotEmpty($contentClassificationLabel->getDescription());
            $this->assertIsString($contentClassificationLabel->getName());
            $this->assertNotEmpty($contentClassificationLabel->getName());
        }
    }
}
