<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeImmutable;
use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\Bits\BitsLeaderboardPeriod;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetBitsLeaderboardRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetCheermotesRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Request\GetExtensionTransactionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\BitsLeaderboardResponse;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\CheermotesResponse;
use SimplyStream\TwitchApi\Helix\Api\Bits\Response\ExtensionTransactionsResponse;
use SimplyStream\TwitchApi\Helix\Api\BitsApi;
use SimplyStream\TwitchApi\Helix\Api\DateRange;
use SimplyStream\TwitchApi\Helix\Models\Bits\BitsLeaderboard;
use SimplyStream\TwitchApi\Helix\Models\Bits\Cheermote;
use SimplyStream\TwitchApi\Helix\Models\Bits\ExtensionTransactions;
use SimplyStream\TwitchApi\Helix\Models\Bits\ProductData;
use SimplyStream\TwitchApi\Helix\Models\Bits\Tier;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsBitsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(BitsApi::class)]
final class BitsApiTest extends TestCase
{
    use BuildsBitsApi;

    #[Test]
    public function get_bits_leaderboard_denormalizes_into_the_response_graph(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'user_id'    => '158010205',
                'user_login' => 'tundracowboy',
                'user_name'  => 'TundraCowboy',
                'rank'       => 1,
                'score'      => 12543,
            ]],
            'date_range' => [
                'started_at' => '2024-01-01T00:00:00Z',
                'ended_at'   => '2024-01-31T00:00:00Z',
            ],
            'total' => 2,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getBitsLeaderboard(
            new GetBitsLeaderboardRequest(),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(BitsLeaderboardResponse::class, $response);
        $this->assertSame(2, $response->total);

        $this->assertInstanceOf(DateRange::class, $response->dateRange);
        $this->assertSame('2024-01-01', $response->dateRange->startedAt->format('Y-m-d'));

        $this->assertCount(1, $response->data);
        $entry = $response->data[0];
        $this->assertInstanceOf(BitsLeaderboard::class, $entry);
        $this->assertSame('158010205', $entry->userId);
        $this->assertSame('TundraCowboy', $entry->userName);
        $this->assertSame(1, $entry->rank);
        $this->assertSame(12543, $entry->score);
    }

    #[Test]
    public function get_bits_leaderboard_unwraps_the_period_enum_and_formats_dates(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data'       => [],
            'date_range' => [
                'started_at' => '2024-01-01T00:00:00Z',
                'ended_at'   => '2024-01-31T00:00:00Z',
            ],
            'total'      => 0,
        ], JSON_THROW_ON_ERROR)));

        $startedAt = new DateTimeImmutable('2024-01-01T00:00:00+00:00');

        $this->buildApi($http)->getBitsLeaderboard(
            new GetBitsLeaderboardRequest(
                count: 10,
                period: BitsLeaderboardPeriod::Week,
                startedAt: $startedAt,
                userId: '1234',
            ),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);

        $this->assertSame([
            'count'      => '10',
            'period'     => 'week',
            'started_at' => $startedAt->format(DATE_RFC3339_EXTENDED),
            'user_id'    => '1234',
        ], $query);
    }

    #[Test]
    public function get_cheermotes_denormalizes_the_nested_tier_list(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'prefix' => 'Cheer',
                'tiers'  => [[
                    'min_bits'           => 1,
                    'id'                 => '1',
                    'color'              => '#979797',
                    'images'             => ['dark' => ['animated' => ['1' => 'https://example.com/1.gif']]],
                    'can_cheer'          => true,
                    'show_in_bits_card'  => true,
                ]],
                'type'          => 'global_first_party',
                'order'         => 1,
                'last_updated'  => '2018-05-22T00:06:04Z',
                'is_charitable' => false,
            ]],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCheermotes(
            new GetCheermotesRequest(),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(CheermotesResponse::class, $response);
        $this->assertCount(1, $response->data);

        $cheermote = $response->data[0];
        $this->assertInstanceOf(Cheermote::class, $cheermote);
        $this->assertSame('Cheer', $cheermote->prefix);
        $this->assertSame('global_first_party', $cheermote->type);
        $this->assertSame(1, $cheermote->order);
        $this->assertFalse($cheermote->isCharitable);
        $this->assertInstanceOf(DateTimeInterface::class, $cheermote->lastUpdated);

        $this->assertCount(1, $cheermote->tiers);
        $tier = $cheermote->tiers[0];
        $this->assertInstanceOf(Tier::class, $tier);
        $this->assertSame(1, $tier->minBits);
        $this->assertSame('#979797', $tier->color);

        // The boolean getter trap: can_cheer / show_in_bits_card must land in the right properties.
        $this->assertTrue($tier->canCheer);
        $this->assertTrue($tier->showInBitsCard);
        $this->assertSame(['dark' => ['animated' => ['1' => 'https://example.com/1.gif']]], $tier->images);
    }

    #[Test]
    public function get_cheermotes_omits_a_null_broadcaster_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getCheermotes(new GetCheermotesRequest(), new StaticAccessToken());

        $this->assertSame('', $http->getLastRequest()->getUri()->getQuery());
    }

    #[Test]
    public function get_extension_transactions_uses_a_path_outside_the_bits_namespace(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'id'                => 'txn-1',
                'timestamp'         => '2019-01-28T04:15:53.325Z',
                'broadcaster_id'    => '439964613',
                'broadcaster_login' => 'chikuseuma',
                'broadcaster_name'  => 'chikuseuma',
                'user_id'           => '424596340',
                'user_login'        => 'quotrok',
                'user_name'         => 'quotrok',
                'product_type'      => 'BITS_IN_EXTENSION',
                'product_data'      => [
                    'sku'            => 'testSku100',
                    'domain'         => 'twitch.ext.some-ext-id',
                    'cost'           => ['amount' => 100, 'type' => 'bits'],
                    'in_development' => false,
                    'display_name'   => 'Test Sku',
                    'expiration'     => '',
                    'broadcast'      => false,
                ],
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getExtensionTransactions(
            new GetExtensionTransactionsRequest(extensionId: 'ext-1', ids: ['txn-1', 'txn-2']),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('/helix/extensions/transactions', $request->getUri()->getPath());
        $this->assertSame('extension_id=ext-1&id=txn-1&id=txn-2&first=20', $request->getUri()->getQuery());

        $this->assertInstanceOf(ExtensionTransactionsResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);
        $this->assertCount(1, $response->data);

        $transaction = $response->data[0];
        $this->assertInstanceOf(ExtensionTransactions::class, $transaction);
        $this->assertSame('txn-1', $transaction->id);
        $this->assertSame('BITS_IN_EXTENSION', $transaction->productType);
        $this->assertInstanceOf(DateTimeInterface::class, $transaction->timestamp);

        $product = $transaction->productData;
        $this->assertInstanceOf(ProductData::class, $product);
        $this->assertSame('testSku100', $product->sku);
        $this->assertFalse($product->inDevelopment);
        $this->assertFalse($product->broadcast);
        $this->assertSame('', $product->expiration);
        $this->assertSame(['amount' => 100, 'type' => 'bits'], $product->cost);
    }

    #[Test]
    public function get_bits_leaderboard_handles_an_empty_date_range_for_the_all_period(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data'       => [],
            'date_range' => ['started_at' => '', 'ended_at' => ''],
            'total'      => 0,
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getBitsLeaderboard(
            new GetBitsLeaderboardRequest(),
            new StaticAccessToken(),
        );

        $this->assertSame(0, $response->total);
    }
}
