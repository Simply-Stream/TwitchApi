<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Locale;
use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Request\GetContentClassificationLabelsRequest;
use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Response\ContentClassificationLabelsResponse;
use SimplyStream\TwitchApi\Helix\Api\ContentClassificationApi;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ContentClassificationApi::class)]
final class ContentClassificationApiTest extends TestCase
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

    private function api(): ContentClassificationApi
    {
        return new ContentClassificationApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function it_defaults_the_locale_to_en_us(): void
    {
        $raw = ['data' => []];
        $expected = new ContentClassificationLabelsResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'content_classification_labels', $this->token, ['locale' => 'en-US'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, ContentClassificationLabelsResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getContentClassificationLabels(new GetContentClassificationLabelsRequest(), $this->token),
        );
    }

    #[Test]
    public function it_unwraps_the_locale_enum(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'content_classification_labels', $this->token, ['locale' => 'de-DE'])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ContentClassificationLabelsResponse(data: []));

        $this->api()->getContentClassificationLabels(
            new GetContentClassificationLabelsRequest(locale: Locale::DeDe),
            $this->token,
        );
    }
}
