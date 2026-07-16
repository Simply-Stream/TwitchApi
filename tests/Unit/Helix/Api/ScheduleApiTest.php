<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\CreateChannelStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\DeleteStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\GetChannelICalendarRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\GetChannelStreamScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\UpdateChannelStreamScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\UpdateChannelStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Response\ChannelStreamScheduleResponse;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Response\StreamScheduleSegmentResponse;
use SimplyStream\TwitchApi\Helix\Api\ScheduleApi;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ChannelStreamSchedule;
use SimplyStream\TwitchApi\Helix\Models\Schedule\CreateChannelStreamScheduleSegment;
use SimplyStream\TwitchApi\Helix\Models\Schedule\UpdateChannelStreamScheduleSegment;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ScheduleApi::class)]
final class ScheduleApiTest extends TestCase
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

    private function api(): ScheduleApi
    {
        return new ScheduleApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    private function schedule(): ChannelStreamSchedule
    {
        return new ChannelStreamSchedule(
            segments: [],
            broadcasterId: '1234',
            broadcasterName: 'Name',
            broadcasterLogin: 'login',
        );
    }

    #[Test]
    public function get_channel_stream_schedule_omits_null_and_empty(): void
    {
        $raw = ['data' => []];
        $expected = new ChannelStreamScheduleResponse(data: $this->schedule());

        // ids defaults to [] -> filtered; startTime/after null -> filtered; broadcasterId + first remain.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'schedule', $this->token, [
                'broadcaster_id' => '1234',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, ChannelStreamScheduleResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getChannelStreamSchedule(
                new GetChannelStreamScheduleRequest(broadcasterId: '1234'),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_channel_stream_schedule_formats_start_time_and_repeats_ids(): void
    {
        $startTime = new \DateTimeImmutable('2024-06-01T00:00:00+00:00');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'schedule', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => ['seg-1', 'seg-2'],
                'start_time'     => $startTime->format(DATE_RFC3339),
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(
            new ChannelStreamScheduleResponse(data: $this->schedule()),
        );

        $this->api()->getChannelStreamSchedule(
            new GetChannelStreamScheduleRequest(broadcasterId: '1234', ids: ['seg-1', 'seg-2'], startTime: $startTime),
            $this->token,
        );
    }

    #[Test]
    public function get_channel_icalendar_calls_the_client_directly(): void
    {
        $ical = "BEGIN:VCALENDAR\r\nEND:VCALENDAR";

        $this->apiClient->expects($this->once())
            ->method('requestICalendar')
            ->with('schedule/icalendar', ['broadcaster_id' => '1234'])
            ->willReturn($ical);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->assertSame(
            $ical,
            $this->api()->getChannelICalendar(new GetChannelICalendarRequest(broadcasterId: '1234')),
        );
    }

    #[Test]
    public function update_channel_stream_schedule_patches_query_without_response(): void
    {
        $vacationStart = new \DateTimeImmutable('2024-07-01T00:00:00+00:00');
        $vacationEnd = new \DateTimeImmutable('2024-07-08T00:00:00+00:00');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'schedule/settings', $this->token, [
                'broadcaster_id'      => '1234',
                'is_vacation_enabled' => true,
                'vacation_start_time' => $vacationStart->format(DATE_RFC3339),
                'vacation_end_time'   => $vacationEnd->format(DATE_RFC3339),
                'timezone'            => 'Europe/Berlin',
            ], [])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->updateChannelStreamSchedule(
            new UpdateChannelStreamScheduleRequest(
                broadcasterId: '1234',
                isVacationEnabled: true,
                vacationStartTime: $vacationStart,
                vacationEndTime: $vacationEnd,
                timezone: 'Europe/Berlin',
            ),
            $this->token,
        );
    }

    #[Test]
    public function update_channel_stream_schedule_omits_null_vacation_fields(): void
    {
        // isVacationEnabled defaults to false (kept, not null); other fields null -> filtered.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'schedule/settings', $this->token, [
                'broadcaster_id'      => '1234',
                'is_vacation_enabled' => false,
            ], [])
            ->willReturn([]);

        $this->api()->updateChannelStreamSchedule(
            new UpdateChannelStreamScheduleRequest(broadcasterId: '1234'),
            $this->token,
        );
    }

    #[Test]
    public function create_channel_stream_schedule_segment_posts_normalized_payload_with_query(): void
    {
        $segment = new CreateChannelStreamScheduleSegment(
            startTime: new \DateTimeImmutable('2024-08-01T18:00:00+00:00'),
            timezone: 'Europe/Berlin',
            duration: '120',
        );
        $normalized = ['start_time' => '2024-08-01T18:00:00+00:00', 'timezone' => 'Europe/Berlin', 'duration' => '120'];
        $raw = ['data' => []];
        $expected = new StreamScheduleSegmentResponse(data: $this->schedule());

        $this->normalizer->expects($this->once())->method('normalize')->with($segment)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'schedule/segment', $this->token, ['broadcaster_id' => '1234'], $normalized)
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, StreamScheduleSegmentResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createChannelStreamScheduleSegment(
                new CreateChannelStreamScheduleSegmentRequest(broadcasterId: '1234', segment: $segment),
                $this->token,
            ),
        );
    }

    #[Test]
    public function update_channel_stream_schedule_segment_patches_normalized_payload_with_id_query(): void
    {
        $segment = new UpdateChannelStreamScheduleSegment(title: 'New title');
        $normalized = ['title' => 'New title'];
        $raw = ['data' => []];
        $expected = new StreamScheduleSegmentResponse(data: $this->schedule());

        $this->normalizer->expects($this->once())->method('normalize')->with($segment)->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'schedule/segment', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => 'seg-1',
            ], $normalized)
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateChannelStreamScheduleSegment(
                new UpdateChannelStreamScheduleSegmentRequest(broadcasterId: '1234', id: 'seg-1', segment: $segment),
                $this->token,
            ),
        );
    }

    #[Test]
    public function delete_stream_schedule_segment_deletes_query(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'schedule/segment', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => 'seg-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');

        $this->api()->deleteStreamScheduleSegment(
            new DeleteStreamScheduleSegmentRequest(broadcasterId: '1234', id: 'seg-1'),
            $this->token,
        );
    }
}
