<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Whispers\Request\SendWhisperRequest;
use SimplyStream\TwitchApi\Helix\Api\WhispersApi;
use SimplyStream\TwitchApi\Helix\Models\Whispers\SendWhisper;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsWhispersApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(WhispersApi::class)]
final class WhispersApiTest extends TestCase
{
    use BuildsWhispersApi;

    #[Test]
    public function send_whisper_sends_the_message_as_body_and_the_ids_as_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->sendWhisper(
            new SendWhisperRequest(
                fromUserId: '123',
                toUserId: '456',
                whisper: new SendWhisper(message: 'hello'),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/whispers', $request->getUri()->getPath());
        $this->assertSame('from_user_id=123&to_user_id=456', $request->getUri()->getQuery());
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['message' => 'hello'], $body);
    }

    #[Test]
    public function send_whisper_url_encodes_a_message_with_special_characters(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->sendWhisper(
            new SendWhisperRequest(
                fromUserId: '123',
                toUserId: '456',
                whisper: new SendWhisper(message: 'Hallo, wie geht\'s? 🎉'),
            ),
            new StaticAccessToken(),
        );

        // The message travels in the body, so no encoding concerns in the query.
        $body = json_decode((string) $http->getLastRequest()->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('Hallo, wie geht\'s? 🎉', $body['message']);
    }
}
