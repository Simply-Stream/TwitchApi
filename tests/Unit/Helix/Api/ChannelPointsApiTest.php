<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ApiClientInterface;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\RedemptionSort;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\RedemptionStatus;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\CreateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\DeleteCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\GetCustomRewardRedemptionRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\GetCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\UpdateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\UpdateRedemptionStatusRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response\CustomRewardRedemptionResponse;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response\CustomRewardResponse;
use SimplyStream\TwitchApi\Helix\Api\ChannelPointsApi;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CreateCustomReward;
use SimplyStream\TwitchApi\Serialization\DenormalizerInterface;
use SimplyStream\TwitchApi\Serialization\NormalizerInterface;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ChannelPointsApi::class)]
final class ChannelPointsApiTest extends TestCase
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

    private function api(): ChannelPointsApi
    {
        return new ChannelPointsApi($this->apiClient, $this->denormalizer, $this->normalizer);
    }

    private function reward(): CreateCustomReward
    {
        return new CreateCustomReward(title: 'Test', cost: 100);
    }

    #[Test]
    public function create_custom_reward_normalizes_the_payload_into_the_body(): void
    {
        $reward = $this->reward();
        $normalized = ['title' => 'Test', 'cost' => 100, 'is_enabled' => true];
        $raw = ['data' => []];
        $expected = new CustomRewardResponse(data: []);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($reward)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('POST', 'channel_points/custom_rewards', $this->token, ['broadcaster_id' => '1234'], $normalized)
            ->willReturn($raw);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($raw, CustomRewardResponse::class)
            ->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->createCustomReward(
                new CreateCustomRewardRequest(broadcasterId: '1234', reward: $reward),
                $this->token,
            ),
        );
    }

    #[Test]
    public function delete_custom_reward_sends_broadcaster_and_id_as_query(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('DELETE', 'channel_points/custom_rewards', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => 'reward-1',
            ])
            ->willReturn([]);

        $this->denormalizer->expects($this->never())->method('denormalize');
        $this->normalizer->expects($this->never())->method('normalize');

        $this->api()->deleteCustomReward(
            new DeleteCustomRewardRequest(broadcasterId: '1234', id: 'reward-1'),
            $this->token,
        );
    }

    #[Test]
    public function get_custom_reward_omits_empty_ids_and_forwards_manageable_flag(): void
    {
        $raw = ['data' => []];
        $expected = new CustomRewardResponse(data: []);

        // ids defaults to [] -> filtered; only_manageable_rewards false is kept ($v !== null).
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channel_points/custom_rewards', $this->token, [
                'broadcaster_id'          => '1234',
                'only_manageable_rewards' => false,
            ])
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getCustomReward(new GetCustomRewardRequest(broadcasterId: '1234'), $this->token),
        );
    }

    #[Test]
    public function get_custom_reward_repeats_ids(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channel_points/custom_rewards', $this->token, [
                'broadcaster_id'          => '1234',
                'id'                      => ['r1', 'r2'],
                'only_manageable_rewards' => true,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new CustomRewardResponse(data: []));

        $this->api()->getCustomReward(
            new GetCustomRewardRequest(broadcasterId: '1234', ids: ['r1', 'r2'], onlyManageableRewards: true),
            $this->token,
        );
    }

    #[Test]
    public function get_custom_reward_redemption_unwraps_enums_and_omits_null(): void
    {
        $raw = ['data' => []];
        $expected = new CustomRewardRedemptionResponse(data: []);

        // status defaults to null and is required only when no ids given -> here ids provided, status stays null.
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channel_points/custom_rewards/redemptions', $this->token, [
                'broadcaster_id' => '1234',
                'reward_id'      => 'reward-1',
                'id'             => ['red-1'],
                'sort'           => 'OLDEST',
                'first'          => 20,
            ])
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->getCustomRewardRedemption(
                new GetCustomRewardRedemptionRequest(
                    broadcasterId: '1234',
                    rewardId: 'reward-1',
                    ids: ['red-1'],
                ),
                $this->token,
            ),
        );
    }

    #[Test]
    public function get_custom_reward_redemption_forwards_status_and_sort_enums(): void
    {
        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('GET', 'channel_points/custom_rewards/redemptions', $this->token, [
                'broadcaster_id' => '1234',
                'reward_id'      => 'reward-1',
                'status'         => 'FULFILLED',
                'sort'           => 'NEWEST',
                'first'          => 20,
            ])
            ->willReturn(['data' => []]);

        $this->denormalizer->method('denormalize')->willReturn(new CustomRewardRedemptionResponse(data: []));

        $this->api()->getCustomRewardRedemption(
            new GetCustomRewardRedemptionRequest(
                broadcasterId: '1234',
                rewardId: 'reward-1',
                status: RedemptionStatus::Fulfilled,
                sort: RedemptionSort::Newest,
            ),
            $this->token,
        );
    }

    #[Test]
    public function update_custom_reward_patches_normalized_payload_with_id_in_query(): void
    {
        $reward = $this->reward();
        $normalized = ['title' => 'Updated', 'cost' => 200];
        $raw = ['data' => []];
        $expected = new CustomRewardResponse(data: []);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($reward)
            ->willReturn($normalized);

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'channel_points/custom_rewards', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => 'reward-1',
            ], $normalized)
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateCustomReward(
                new UpdateCustomRewardRequest(broadcasterId: '1234', id: 'reward-1', reward: $reward),
                $this->token,
            ),
        );
    }

    #[Test]
    public function update_redemption_status_maps_status_to_body_and_ids_to_query(): void
    {
        $raw = ['data' => []];
        $expected = new CustomRewardRedemptionResponse(data: []);

        // Small single-field body is mapped manually, not via the normalizer.
        $this->normalizer->expects($this->never())->method('normalize');

        $this->apiClient->expects($this->once())
            ->method('request')
            ->with('PATCH', 'channel_points/custom_rewards/redemptions', $this->token, [
                'broadcaster_id' => '1234',
                'id'             => ['red-1', 'red-2'],
                'reward_id'      => 'reward-1',
            ], ['status' => 'FULFILLED'])
            ->willReturn($raw);

        $this->denormalizer->method('denormalize')->willReturn($expected);

        $this->assertSame(
            $expected,
            $this->api()->updateRedemptionStatus(
                new UpdateRedemptionStatusRequest(
                    broadcasterId: '1234',
                    ids: ['red-1', 'red-2'],
                    rewardId: 'reward-1',
                    status: RedemptionStatus::Fulfilled,
                ),
                $this->token,
            ),
        );
    }
}
