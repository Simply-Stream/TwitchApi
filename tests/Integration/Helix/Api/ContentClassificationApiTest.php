<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Locale;
use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Request\GetContentClassificationLabelsRequest;
use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Response\ContentClassificationLabelsResponse;
use SimplyStream\TwitchApi\Helix\Api\ContentClassificationApi;
use SimplyStream\TwitchApi\Helix\Models\CCLs\ContentClassificationLabel;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsContentClassificationApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ContentClassificationApi::class)]
final class ContentClassificationApiTest extends TestCase
{
    use BuildsContentClassificationApi;

    #[Test]
    public function get_content_classification_labels_denormalizes_the_label_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                [
                    'id'          => 'DrugsIntoxication',
                    'description' => 'Excessive tobacco glorification or promotion, any marijuana consumption/drug '
                        . 'paraphernalia, or drug abuse.',
                    'name'        => 'Drugs, Intoxication, or Excessive Tobacco Use',
                ],
                [
                    'id'          => 'Gambling',
                    'description' => 'Participating in online or in-person gambling, poker or fantasy sports.',
                    'name'        => 'Gambling',
                ],
            ],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getContentClassificationLabels(
            new GetContentClassificationLabelsRequest(),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/content_classification_labels', $request->getUri()->getPath());
        $this->assertSame('locale=en-US', $request->getUri()->getQuery());

        $this->assertInstanceOf(ContentClassificationLabelsResponse::class, $response);
        $this->assertCount(2, $response->data);

        $label = $response->data[0];
        $this->assertInstanceOf(ContentClassificationLabel::class, $label);
        $this->assertSame('DrugsIntoxication', $label->id);
        $this->assertSame('Drugs, Intoxication, or Excessive Tobacco Use', $label->name);
        $this->assertStringContainsString('marijuana', $label->description);

        $this->assertSame('Gambling', $response->data[1]->id);
    }

    #[Test]
    public function get_content_classification_labels_unwraps_a_non_default_locale(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'          => 'Gambling',
                'description' => 'Teilnahme an Online- oder Offline-Glücksspielen.',
                'name'        => 'Glücksspiel',
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getContentClassificationLabels(
            new GetContentClassificationLabelsRequest(locale: Locale::DeDe),
            new StaticAccessToken(),
        );

        $this->assertSame('locale=de-DE', $http->getLastRequest()->getUri()->getQuery());
        $this->assertSame('Glücksspiel', $response->data[0]->name);
    }
}
