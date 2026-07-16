<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClient;
use SimplyStream\TwitchApi\Helix\Api\TwitchApiException;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ApiClient::class)]
final class ApiClientTest extends TestCase
{
    private MockHttpClient $http;
    private ApiClient $client;

    protected function setUp(): void
    {
        $this->http = new MockHttpClient();
        $factory = new Psr17Factory();
        $this->client = new ApiClient($this->http, $factory, $factory, 'client-id');
    }

    #[Test]
    public function it_builds_the_uri_from_base_url_and_path(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('GET', 'eventsub/subscriptions', new StaticAccessToken());

        $this->assertSame(
            'https://api.twitch.tv/helix/eventsub/subscriptions',
            (string) $this->http->getLastRequest()->getUri(),
        );
    }

    #[Test]
    public function it_strips_leading_slashes_from_the_path(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('GET', '/eventsub/subscriptions', new StaticAccessToken());

        $this->assertStringEndsWith('/helix/eventsub/subscriptions', (string) $this->http->getLastRequest()->getUri());
    }

    #[Test]
    public function it_sets_the_auth_and_accept_headers(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('GET', 'users', new StaticAccessToken());

        $request = $this->http->getLastRequest();
        $this->assertSame('client-id', $request->getHeaderLine('Client-Id'));
        $this->assertSame('Bearer test-token', $request->getHeaderLine('Authorization'));
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
    }

    #[Test]
    public function it_repeats_query_keys_instead_of_indexing_arrays(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('GET', 'users', new StaticAccessToken(), [
            'login'   => 'foo',
            'user_id' => ['1', '2', '3'],
        ]);

        $uri = (string) $this->http->getLastRequest()->getUri();
        $this->assertStringContainsString('login=foo', $uri);
        $this->assertStringContainsString('user_id=1&user_id=2&user_id=3', $uri);
        $this->assertStringNotContainsString('user_id%5B0%5D', $uri);
    }

    #[Test]
    public function it_stringifies_booleans_as_literals(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('GET', 'clips', new StaticAccessToken(), [
            'has_delay'    => false,
            'is_featured'  => true,
        ]);

        $uri = (string) $this->http->getLastRequest()->getUri();
        $this->assertStringContainsString('has_delay=false', $uri);
        $this->assertStringContainsString('is_featured=true', $uri);
        $this->assertStringNotContainsString('has_delay=1', $uri);
        $this->assertStringNotContainsString('has_delay=&', $uri);
    }

    #[Test]
    public function it_rawurlencodes_query_keys_and_values(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('GET', 'search/channels', new StaticAccessToken(), [
            'query' => 'angel of death',
        ]);

        $this->assertStringContainsString('query=angel%20of%20death', (string) $this->http->getLastRequest()->getUri());
    }

    #[Test]
    public function it_serializes_the_body_and_sets_content_type(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('POST', 'eventsub/subscriptions', new StaticAccessToken(), [], [
            'type'    => 'user.update',
            'version' => '1',
        ]);

        $request = $this->http->getLastRequest();
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertSame('{"type":"user.update","version":"1"}', (string) $request->getBody());
    }

    #[Test]
    public function it_does_not_set_a_body_when_none_is_given(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client->request('GET', 'users', new StaticAccessToken());

        $request = $this->http->getLastRequest();
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
        $this->assertSame('', (string) $request->getBody());
    }

    #[Test]
    public function it_returns_the_decoded_body_on_success(): void
    {
        $this->http->addResponse(new Response(200, [], '{"data":[{"id":"1"}],"total":1}'));

        $result = $this->client->request('GET', 'users', new StaticAccessToken());

        $this->assertSame(['data' => [['id' => '1']], 'total' => 1], $result);
    }

    #[Test]
    public function it_returns_an_empty_array_on_204(): void
    {
        $this->http->addResponse(new Response(204, [], ''));

        $result = $this->client->request('DELETE', 'moderation/bans', new StaticAccessToken(), ['id' => 'x']);

        $this->assertSame([], $result);
    }

    #[Test]
    public function it_returns_an_empty_array_on_empty_body(): void
    {
        $this->http->addResponse(new Response(200, [], ''));

        $result = $this->client->request('GET', 'users', new StaticAccessToken());

        $this->assertSame([], $result);
    }

    #[Test]
    public function it_throws_a_typed_exception_on_error_status(): void
    {
        $this->http->addResponse(new Response(401, [], json_encode([
            'error'   => 'Unauthorized',
            'status'  => 401,
            'message' => 'Invalid OAuth token',
        ], JSON_THROW_ON_ERROR)));

        try {
            $this->client->request('GET', 'users', new StaticAccessToken());
            $this->fail('Expected TwitchApiException');
        } catch (TwitchApiException $e) {
            $this->assertSame(401, $e->status);
            $this->assertSame('Unauthorized', $e->error);
            $this->assertSame('Invalid OAuth token', $e->getMessage());
        }
    }

    #[Test]
    public function icalendar_requests_the_calendar_accept_header_without_auth(): void
    {
        $this->http->addResponse(new Response(200, [], 'BEGIN:VCALENDAR'));

        $this->client->requestICalendar('schedule/icalendar', ['broadcaster_id' => '1234']);

        $request = $this->http->getLastRequest();
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('text/calendar', $request->getHeaderLine('Accept'));
        $this->assertSame('client-id', $request->getHeaderLine('Client-Id'));
        $this->assertFalse($request->hasHeader('Authorization'));
        $this->assertStringContainsString('broadcaster_id=1234', (string) $request->getUri());
    }

    #[Test]
    public function icalendar_returns_the_raw_body(): void
    {
        $ical = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nEND:VCALENDAR";
        $this->http->addResponse(new Response(200, [], $ical));

        $result = $this->client->requestICalendar('schedule/icalendar', ['broadcaster_id' => '1234']);

        $this->assertSame($ical, $result);
    }

    #[Test]
    public function icalendar_throws_a_typed_exception_on_error_status(): void
    {
        $this->http->addResponse(new Response(404, [], 'not found'));

        $this->expectException(TwitchApiException::class);

        $this->client->requestICalendar('schedule/icalendar', ['broadcaster_id' => 'unknown']);
    }
}
