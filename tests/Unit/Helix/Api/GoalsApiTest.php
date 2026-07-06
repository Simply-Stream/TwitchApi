<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Goals\Request\GetCreatorGoalsRequest;
use SimplyStream\TwitchApi\Helix\Api\Goals\Response\CreatorGoalsResponse;
use SimplyStream\TwitchApi\Helix\Api\GoalsApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(GoalsApi::class)]
final class GoalsApiTest extends TestCase
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

    private function api(): GoalsApi
    {
        return new GoalsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_creator_goals_forwards_broadcaster_id(): void
    {
        $raw = ['data' => []];
        $expected = new CreatorGoalsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'goals', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CreatorGoalsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getCreatorGoals(new GetCreatorGoalsRequest(broadcasterId: '1234'), $this->token),
        );
    }
}
