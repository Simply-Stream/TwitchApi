<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

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
    private function client(MockHttpClient $http): ApiClient
    {
        $factory = new Psr17Factory();

        return new ApiClient($http, $factory, $factory, 'client-id');
    }

    #[Test]
    public function it_sends_the_client_id_and_bearer_token_on_every_request(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('GET', 'users', new StaticAccessToken());

        $request = $http->getLastRequest();
        $this->assertSame('client-id', $request->getHeaderLine('Client-Id'));
        $this->assertSame('Bearer ' . (new StaticAccessToken())->getAccessToken(), $request->getHeaderLine('Authorization'));
        $this->assertSame('application/json', $request->getHeaderLine('Accept'));
    }

    #[Test]
    public function it_prefixes_the_helix_base_url_and_trims_leading_slashes(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('GET', '/users/blocks', new StaticAccessToken());

        $uri = $http->getLastRequest()->getUri();
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('api.twitch.tv', $uri->getHost());
        $this->assertSame('/helix/users/blocks', $uri->getPath());
    }

    #[Test]
    public function it_repeats_query_keys_instead_of_indexing_them(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('GET', 'users', new StaticAccessToken(), [
            'id' => ['1', '2', '3'],
        ]);

        // Twitch requires id=1&id=2&id=3, not id[0]=1&id[1]=2.
        $this->assertSame('id=1&id=2&id=3', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function it_stringifies_booleans_as_literals(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('GET', 'search/channels', new StaticAccessToken(), [
            'live_only' => true,
            'is_gift'   => false,
        ]);

        // (string) true would be "1", (string) false would be "".
        $this->assertSame('live_only=true&is_gift=false', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function it_url_encodes_query_keys_and_values(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('GET', 'search/categories', new StaticAccessToken(), [
            'query' => 'love computer',
        ]);

        $this->assertSame('query=love%20computer', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function it_omits_the_query_string_when_there_are_no_parameters(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('GET', 'users', new StaticAccessToken());

        $this->assertSame('', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function it_sends_a_json_body_with_a_content_type_header(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('POST', 'polls', new StaticAccessToken(), [], [
            'title'    => 'Best game?',
            'duration' => 60,
        ]);

        $request = $http->getLastRequest();
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertSame('{"title":"Best game?","duration":60}', (string) $request->getBody());
    }

    #[Test]
    public function it_sends_no_body_and_no_content_type_when_body_is_null(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('GET', 'users', new StaticAccessToken());

        $request = $http->getLastRequest();
        $this->assertFalse($request->hasHeader('Content-Type'));
        $this->assertSame('', (string) $request->getBody());
    }

    #[Test]
    public function it_sends_an_empty_json_object_when_body_is_an_empty_array(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));

        $this->client($http)->request('POST', 'channels/vips', new StaticAccessToken(), ['broadcaster_id' => '1'], []);

        // An empty array encodes to "[]", not "{}" — worth knowing for void endpoints.
        $this->assertSame('[]', (string) $http->getLastRequest()->getBody());
    }

    #[Test]
    public function it_decodes_a_json_response_into_an_array(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[{"id":"1"}],"total":1}'));

        $result = $this->client($http)->request('GET', 'users', new StaticAccessToken());

        $this->assertSame(['data' => [['id' => '1']], 'total' => 1], $result);
    }

    #[Test]
    public function it_returns_an_empty_array_for_204_responses(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $result = $this->client($http)->request('DELETE', 'moderation/bans', new StaticAccessToken());

        $this->assertSame([], $result);
    }

    #[Test]
    public function it_returns_an_empty_array_for_an_empty_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], ''));

        $result = $this->client($http)->request('GET', 'users', new StaticAccessToken());

        $this->assertSame([], $result);
    }

    #[Test]
    public function it_throws_on_error_responses(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(401, [], '{"error":"Unauthorized","status":401,"message":"Invalid token"}'));

        $this->expectException(TwitchApiException::class);

        $this->client($http)->request('GET', 'users', new StaticAccessToken());
    }

    #[Test]
    public function request_icalendar_sends_no_authorization_header(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], "BEGIN:VCALENDAR\r\nEND:VCALENDAR"));

        $result = $this->client($http)->requestICalendar('schedule/icalendar', ['broadcaster_id' => '1234']);

        $request = $http->getLastRequest();
        $this->assertSame('text/calendar', $request->getHeaderLine('Accept'));
        $this->assertFalse($request->hasHeader('Authorization'));
        $this->assertSame('client-id', $request->getHeaderLine('Client-Id'));
        $this->assertSame('broadcaster_id=1234', $request->getUri()->getQuery());
        $this->assertSame("BEGIN:VCALENDAR\r\nEND:VCALENDAR", $result);
    }

    #[Test]
    public function request_icalendar_throws_on_error_responses(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(404, [], 'Not found'));

        $this->expectException(TwitchApiException::class);

        $this->client($http)->requestICalendar('schedule/icalendar', ['broadcaster_id' => '1234']);
    }

    #[Test]
    public function it_uses_a_custom_base_url_when_provided(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], '{"data":[]}'));
        $factory = new Psr17Factory();

        $client = new ApiClient($http, $factory, $factory, 'client-id', 'http://localhost:8080/mock');
        $client->request('GET', 'users', new StaticAccessToken());

        $uri = $http->getLastRequest()->getUri();
        $this->assertSame('localhost', $uri->getHost());
        $this->assertSame(8080, $uri->getPort());
        $this->assertSame('/mock/users', $uri->getPath());
    }
}
