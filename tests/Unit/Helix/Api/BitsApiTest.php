<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\Bits\BitsLeaderboardPeriod;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetBitsLeaderboardRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetCheermotesRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetExtensionTransactionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\BitsLeaderboardResponse;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\CheermotesResponse;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\ExtensionTransactionsResponse;
use SimplyStream\TwitchApi\Helix\Api\BitsApi;
use SimplyStream\TwitchApi\Helix\Api\DateRange;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(BitsApi::class)]
final class BitsApiTest extends TestCase
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

    private function api(): BitsApi
    {
        return new BitsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    #[Test]
    public function get_bits_leaderboard_unwraps_the_period_enum_and_omits_null(): void
    {
        $raw = ['data' => [], 'date_range' => ['started_at' => '', 'ended_at' => ''], 'total' => 0];
        $expected = new BitsLeaderboardResponse(
            data: [],
            dateRange: new DateRange(
                new \DateTimeImmutable('2024-01-01T00:00:00+00:00'),
                new \DateTimeImmutable('2024-01-31T00:00:00+00:00'),
            ),
            total: 0,
        );

        // startedAt and userId default to null -> filtered out; count and period always present.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'bits/leaderboard', $this->token, [
                'count'  => 10,
                'period' => 'all',
            ])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, BitsLeaderboardResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getBitsLeaderboard(new GetBitsLeaderboardRequest(), $this->token),
        );
    }

    #[Test]
    public function get_bits_leaderboard_forwards_all_parameters(): void
    {
        $startedAt = new \DateTimeImmutable('2022-01-01T00:00:00+00:00');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'bits/leaderboard', $this->token, [
                'count'      => 5,
                'period'     => 'week',
                'started_at' => $startedAt->format(DATE_RFC3339_EXTENDED),
                'user_id'    => 'user-1',
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(
            new BitsLeaderboardResponse(
                data: [],
                dateRange: new DateRange(
                    new \DateTimeImmutable('2022-01-01T00:00:00+00:00'),
                    new \DateTimeImmutable('2022-01-08T00:00:00+00:00'),
                ),
                total: 0,
            ),
        );

        $this->api()->getBitsLeaderboard(
            new GetBitsLeaderboardRequest(
                count: 5,
                period: BitsLeaderboardPeriod::Week,
                startedAt: $startedAt,
                userId: 'user-1',
            ),
            $this->token,
        );
    }

    #[Test]
    public function get_cheermotes_forwards_broadcaster_id(): void
    {
        $raw = ['data' => []];
        $expected = new CheermotesResponse(data: []);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'bits/cheermotes', $this->token, ['broadcaster_id' => '1234'])
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CheermotesResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getCheermotes(new GetCheermotesRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_cheermotes_omits_null_broadcaster_id(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'bits/cheermotes', $this->token, [])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new CheermotesResponse(data: []));

        $this->api()->getCheermotes(new GetCheermotesRequest(), $this->token);
    }

    #[Test]
    public function get_extension_transactions_uses_non_base_path_and_repeats_ids(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/transactions', $this->token, [
                'extension_id' => 'ext-1',
                'id'           => ['t1', 't2'],
                'first'        => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ExtensionTransactionsResponse(data: []));

        $this->api()->getExtensionTransactions(
            new GetExtensionTransactionsRequest(extensionId: 'ext-1', ids: ['t1', 't2']),
            $this->token,
        );
    }

    #[Test]
    public function get_extension_transactions_omits_empty_ids_list(): void
    {
        // ids defaults to [] -> filtered by the $v !== [] guard, not sent as an empty key.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'extensions/transactions', $this->token, [
                'extension_id' => 'ext-1',
                'first'        => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new ExtensionTransactionsResponse(data: []));

        $this->api()->getExtensionTransactions(
            new GetExtensionTransactionsRequest(extensionId: 'ext-1'),
            $this->token,
        );
    }
}
