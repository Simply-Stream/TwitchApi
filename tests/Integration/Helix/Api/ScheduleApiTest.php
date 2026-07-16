<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeImmutable;
use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\CreateChannelStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\DeleteStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\GetChannelICalendarRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\GetChannelStreamScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\UpdateChannelStreamScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\UpdateChannelStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Response\ChannelStreamScheduleResponse;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Response\StreamScheduleSegmentResponse;
use SimplyStream\TwitchApi\Helix\Api\ScheduleApi;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Category;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ChannelStreamSchedule;
use SimplyStream\TwitchApi\Helix\Models\Schedule\CreateChannelStreamScheduleSegment;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ScheduleSegment;
use SimplyStream\TwitchApi\Helix\Models\Schedule\UpdateChannelStreamScheduleSegment;
use SimplyStream\TwitchApi\Helix\Models\Schedule\Vacation;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsScheduleApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ScheduleApi::class)]
final class ScheduleApiTest extends TestCase
{
    use BuildsScheduleApi;

    /** @return array<string, mixed> */
    private function segmentPayload(): array
    {
        return [
            'id'              => 'eyJzZWdtZW50SUQiOiJlNGFjYzcyNC0zNzFmLTQwMlctODFjYS0yM2FkYTc5NzU5ZDQiLCJpc29ZZWFyIjoyMDIxLCJpc29XZWVrIjoyNn0=',
            'start_time'      => '2021-07-01T18:00:00Z',
            'end_time'        => '2021-07-01T19:00:00Z',
            'title'           => 'TwitchDev Monthly Update // July 1, 2021',
            'canceled_until'  => null,
            'category'        => [
                'id'   => '509670',
                'name' => 'Science & Technology',
            ],
            'is_recurring'    => false,
        ];
    }

    #[Test]
    public function get_channel_stream_schedule_denormalizes_a_single_schedule_object(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                'segments'          => [$this->segmentPayload()],
                'broadcaster_id'    => '141981764',
                'broadcaster_name'  => 'TwitchDev',
                'broadcaster_login' => 'twitchdev',
                'vacation'          => null,
            ],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelStreamSchedule(
            new GetChannelStreamScheduleRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/schedule', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(ChannelStreamScheduleResponse::class, $response);

        // data is a single object here, not a list.
        $schedule = $response->data;
        $this->assertInstanceOf(ChannelStreamSchedule::class, $schedule);
        $this->assertSame('TwitchDev', $schedule->broadcasterName);
        $this->assertNull($schedule->vacation);

        $this->assertCount(1, $schedule->segments);
        $segment = $schedule->segments[0];
        $this->assertInstanceOf(ScheduleSegment::class, $segment);
        $this->assertSame('TwitchDev Monthly Update // July 1, 2021', $segment->title);
        $this->assertFalse($segment->isRecurring);
        $this->assertNull($segment->canceledUntil);
        $this->assertInstanceOf(DateTimeInterface::class, $segment->startTime);
        $this->assertInstanceOf(DateTimeInterface::class, $segment->endTime);

        $this->assertInstanceOf(Category::class, $segment->category);
        $this->assertSame('509670', $segment->category->id);
        $this->assertSame('Science & Technology', $segment->category->name);
    }

    #[Test]
    public function get_channel_stream_schedule_denormalizes_a_vacation(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                'segments'          => [],
                'broadcaster_id'    => '141981764',
                'broadcaster_name'  => 'TwitchDev',
                'broadcaster_login' => 'twitchdev',
                'vacation'          => [
                    'start_time' => '2021-08-30T07:00:00Z',
                    'end_time'   => '2021-09-05T07:00:00Z',
                ],
            ],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getChannelStreamSchedule(
            new GetChannelStreamScheduleRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        $vacation = $response->data->vacation;
        $this->assertInstanceOf(Vacation::class, $vacation);
        $this->assertInstanceOf(DateTimeInterface::class, $vacation->startTime);
        $this->assertSame('2021-08-30', $vacation->startTime->format('Y-m-d'));
    }

    #[Test]
    public function get_channel_stream_schedule_repeats_ids_and_formats_the_start_time(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                'segments'          => [],
                'broadcaster_id'    => '141981764',
                'broadcaster_name'  => 'TwitchDev',
                'broadcaster_login' => 'twitchdev',
                'vacation'          => null,
            ],
        ], JSON_THROW_ON_ERROR)));

        $startTime = new DateTimeImmutable('2021-07-01T18:00:00+00:00');

        $this->buildApi($http)->getChannelStreamSchedule(
            new GetChannelStreamScheduleRequest(
                broadcasterId: '141981764',
                ids: ['seg-1', 'seg-2'],
                startTime: $startTime,
                after: 'cursor-1',
            ),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertSame($startTime->format(DATE_RFC3339), $query['start_time']);
        $this->assertSame('cursor-1', $query['after']);
        $this->assertStringContainsString('id=seg-1&id=seg-2', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function get_channel_icalendar_returns_the_raw_body_without_authorization(): void
    {
        $ical = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nEND:VCALENDAR";

        $http = new MockHttpClient();
        $http->addResponse(new Response(200, ['Content-Type' => 'text/calendar; charset=utf-8'], $ical));

        $result = $this->buildApi($http)->getChannelICalendar(
            new GetChannelICalendarRequest(broadcasterId: '141981764'),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/schedule/icalendar', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764', $request->getUri()->getQuery());
        $this->assertSame('text/calendar', $request->getHeaderLine('Accept'));

        // This endpoint takes no token.
        $this->assertFalse($request->hasHeader('Authorization'));

        $this->assertSame($ical, $result);
    }

    #[Test]
    public function update_channel_stream_schedule_sends_vacation_times_as_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $start = new DateTimeImmutable('2021-05-16T00:00:00+00:00');
        $end = new DateTimeImmutable('2021-05-23T00:00:00+00:00');

        $this->buildApi($http)->updateChannelStreamSchedule(
            new UpdateChannelStreamScheduleRequest(
                broadcasterId: '141981764',
                isVacationEnabled: true,
                vacationStartTime: $start,
                vacationEndTime: $end,
                timezone: 'America/New_York',
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame('/helix/schedule/settings', $request->getUri()->getPath());

        parse_str($request->getUri()->getQuery(), $query);
        $this->assertSame('true', $query['is_vacation_enabled']);
        $this->assertSame($start->format(DATE_RFC3339), $query['vacation_start_time']);
        $this->assertSame('America/New_York', $query['timezone']);
    }

    #[Test]
    public function update_channel_stream_schedule_omits_null_vacation_fields(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->updateChannelStreamSchedule(
            new UpdateChannelStreamScheduleRequest(broadcasterId: '141981764'),
            new StaticAccessToken(),
        );

        // isVacationEnabled defaults to false, which survives the null filter.
        $this->assertSame(
            'broadcaster_id=141981764&is_vacation_enabled=false',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function create_channel_stream_schedule_segment_sends_body_and_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                'segments'          => [$this->segmentPayload()],
                'broadcaster_id'    => '141981764',
                'broadcaster_name'  => 'TwitchDev',
                'broadcaster_login' => 'twitchdev',
                'vacation'          => null,
            ],
        ], JSON_THROW_ON_ERROR)));

        $startTime = new DateTimeImmutable('2021-07-01T18:00:00+00:00');

        $response = $this->buildApi($http)->createChannelStreamScheduleSegment(
            new CreateChannelStreamScheduleSegmentRequest(
                broadcasterId: '141981764',
                segment: new CreateChannelStreamScheduleSegment(
                    startTime: $startTime,
                    timezone: 'America/New_York',
                    duration: '60',
                ),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/schedule/segment', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('America/New_York', $body['timezone']);
        $this->assertSame('60', $body['duration']);
        $this->assertArrayHasKey('start_time', $body);

        $this->assertInstanceOf(StreamScheduleSegmentResponse::class, $response);
        $this->assertInstanceOf(ChannelStreamSchedule::class, $response->data);
    }

    #[Test]
    public function update_channel_stream_schedule_segment_patches_with_the_segment_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [
                'segments'          => [$this->segmentPayload()],
                'broadcaster_id'    => '141981764',
                'broadcaster_name'  => 'TwitchDev',
                'broadcaster_login' => 'twitchdev',
                'vacation'          => null,
            ],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateChannelStreamScheduleSegment(
            new UpdateChannelStreamScheduleSegmentRequest(
                broadcasterId: '141981764',
                id: 'seg-1',
                segment: new UpdateChannelStreamScheduleSegment(title: 'TwitchDev Monthly Update'),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame('broadcaster_id=141981764&id=seg-1', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        // Only the field that was set.
        $this->assertSame(['title' => 'TwitchDev Monthly Update'], $body);
    }

    #[Test]
    public function delete_stream_schedule_segment_sends_a_delete(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteStreamScheduleSegment(
            new DeleteStreamScheduleSegmentRequest(broadcasterId: '141981764', id: 'seg-1'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('/helix/schedule/segment', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=141981764&id=seg-1', $request->getUri()->getQuery());
    }
}
