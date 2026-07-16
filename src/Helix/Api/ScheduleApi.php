<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\CreateChannelStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\DeleteStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\GetChannelICalendarRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\GetChannelStreamScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\UpdateChannelStreamScheduleRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Request\UpdateChannelStreamScheduleSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Response\ChannelStreamScheduleResponse;
use SimplyStream\TwitchApi\Helix\Api\Schedule\Response\StreamScheduleSegmentResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class ScheduleApi extends AbstractApi
{
    private const string BASE_PATH = 'schedule';

    /**
     * Gets the broadcaster’s streaming schedule. You can get the entire schedule or specific segments of the schedule.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/schedule
     *
     * @see https://help.twitch.tv/s/article/channel-page-setup#Schedule Schedule
     *
     * @param AccessTokenInterface            $accessToken Requires an app access token or user access token.
     */
    public function getChannelStreamSchedule(
        GetChannelStreamScheduleRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelStreamScheduleResponse {
        // utc_offset is listed in Twitch's docs but not actually supported, so it is intentionally omitted.
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->ids,
                'start_time'     => $request->startTime?->format(DATE_RFC3339),
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH, ChannelStreamScheduleResponse::class, $accessToken, $query);
    }

    /**
     * Gets the broadcaster’s streaming schedule as an iCalendar.
     *
     * Authorization
     * The Client-Id and Authorization headers are not required.
     *
     * URL
     * GET https://api.twitch.tv/helix/schedule/icalendar
     *
     * @see https://datatracker.ietf.org/doc/html/rfc5545
     *
     * @return string The schedule in iCalendar (RFC5545) format.
     */
    public function getChannelICalendar(
        GetChannelICalendarRequest $request,
    ): string {
        return $this->apiClient->requestICalendar(
            self::BASE_PATH . '/icalendar',
            ['broadcaster_id' => $request->broadcasterId],
        );
    }

    /**
     * Updates the broadcaster’s schedule settings, such as scheduling a vacation.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:schedule scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/schedule/settings
     *
     * @param AccessTokenInterface               $accessToken Requires a user access token that includes the
     *                                                        channel:manage:schedule scope.
     */
    public function updateChannelStreamSchedule(
        UpdateChannelStreamScheduleRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $query = array_filter(
            [
                'broadcaster_id'      => $request->broadcasterId,
                'is_vacation_enabled' => $request->isVacationEnabled,
                'vacation_start_time' => $request->vacationStartTime?->format(DATE_RFC3339),
                'vacation_end_time'   => $request->vacationEndTime?->format(DATE_RFC3339),
                'timezone'            => $request->timezone,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        $this->patchWithoutResponse(self::BASE_PATH . '/settings', $accessToken, query: $query);
    }

    /**
     * Adds a single or recurring broadcast to the broadcaster’s streaming schedule. For information about scheduling
     * broadcasts, see Stream Schedule.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:schedule scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/schedule/segment
     *
     * @param AccessTokenInterface                      $accessToken Requires a user access token that includes the
     *                                                               channel:manage:schedule scope.
     */
    public function createChannelStreamScheduleSegment(
        CreateChannelStreamScheduleSegmentRequest $request,
        AccessTokenInterface $accessToken,
    ): StreamScheduleSegmentResponse {
        return $this->post(
            self::BASE_PATH . '/segment',
            StreamScheduleSegmentResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->segment),
            ['broadcaster_id' => $request->broadcasterId],
        );
    }

    /**
     * Updates a scheduled broadcast segment.
     *
     * For recurring segments, updating a segment’s title, category, duration, and timezone, changes all segments in
     * the recurring schedule, not just the specified segment.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:schedule scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/schedule/segment
     *
     * @param AccessTokenInterface                      $accessToken Requires a user access token that includes the
     *                                                               channel:manage:schedule scope.
     */
    public function updateChannelStreamScheduleSegment(
        UpdateChannelStreamScheduleSegmentRequest $request,
        AccessTokenInterface $accessToken,
    ): StreamScheduleSegmentResponse {
        return $this->patch(
            self::BASE_PATH . '/segment',
            StreamScheduleSegmentResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->segment),
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->id,
            ],
        );
    }

    /**
     * Removes a broadcast segment from the broadcaster’s streaming schedule.
     *
     * NOTE: For recurring segments, removing a segment removes all segments in the recurring schedule.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:schedule scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/schedule/segment
     *
     * @param AccessTokenInterface               $accessToken Requires a user access token that includes the
     *                                                        channel:manage:schedule scope.
     */
    public function deleteStreamScheduleSegment(
        DeleteStreamScheduleSegmentRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH . '/segment',
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->id,
            ],
        );
    }
}
