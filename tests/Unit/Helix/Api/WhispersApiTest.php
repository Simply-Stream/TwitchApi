<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Whispers\Request\SendWhisperRequest;
use SimplyStream\TwitchApi\Helix\Api\WhispersApi;
use SimplyStream\TwitchApi\Helix\Models\Whispers\SendWhisper;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(WhispersApi::class)]
final class WhispersApiTest extends TestCase
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

    private function api(): WhispersApi
    {
        return new WhispersApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function send_whisper_posts_normalized_body_with_user_ids_in_query(): void
    {
        $whisper = new SendWhisper(message: 'Hello there');
        $normalized = ['message' => 'Hello there'];

        $this->normalizer->expects($this->once())->method('normalize')->with($whisper)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'whispers', $this->token, [
                'from_user_id' => 'from-1',
                'to_user_id'   => 'to-1',
            ], $normalized)
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->sendWhisper(
            new SendWhisperRequest(fromUserId: 'from-1', toUserId: 'to-1', whisper: $whisper),
            $this->token,
        );
    }
}
