<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Integration\Helix\Api;

use DateTimeInterface;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\CreateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\DeleteCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\GetCustomRewardRedemptionRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\GetCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\UpdateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\UpdateRedemptionStatusRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\RedemptionStatus;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response\CustomRewardRedemptionResponse;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response\CustomRewardResponse;
use SimplyStream\TwitchApi\Helix\Api\ChannelPointsApi;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CreateCustomReward;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomReward;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomRewardRedemption;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\GlobalCooldownSetting;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\MaxPerStreamSetting;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\MaxPerUserPerStreamSetting;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\Reward;
use SimplyStream\TwitchApi\Tests\Helper\Builder\BuildsChannelPointsApi;
use SimplyStream\TwitchApi\Tests\Helper\MockHttpClient;
use SimplyStream\TwitchApi\Tests\Helper\StaticAccessToken;

#[CoversClass(ChannelPointsApi::class)]
final class ChannelPointsApiTest extends TestCase
{
    use BuildsChannelPointsApi;

    /** @return array<string, mixed> */
    private function rewardPayload(): array
    {
        return [
            'broadcaster_id'    => '274637212',
            'broadcaster_login' => 'torpedo09',
            'broadcaster_name'  => 'torpedo09',
            'id'                => '92af127c-7326-4483-a52b-b0da0be61c01',
            'title'             => 'game analysis 1v1',
            'prompt'            => '',
            'cost'              => 50000,
            'default_image'     => [
                'url_1x' => 'https://static-cdn.jtvnw.net/custom-reward-images/default-1.png',
                'url_2x' => 'https://static-cdn.jtvnw.net/custom-reward-images/default-2.png',
                'url_4x' => 'https://static-cdn.jtvnw.net/custom-reward-images/default-4.png',
            ],
            'background_color'  => '#00E5CB',
            'is_enabled'        => true,
            'is_user_input_required' => false,
            'max_per_stream_setting' => [
                'is_enabled'     => true,
                'max_per_stream' => 10,
            ],
            'max_per_user_per_stream_setting' => [
                'is_enabled'              => true,
                'max_per_user_per_stream' => 2,
            ],
            'global_cooldown_setting' => [
                'is_enabled'              => true,
                'global_cooldown_seconds' => 300,
            ],
            'is_paused'   => false,
            'is_in_stock' => true,
            'should_redemptions_skip_request_queue' => false,
            'redemptions_redeemed_current_stream'   => null,
            'image'               => null,
            'cooldown_expires_at' => null,
        ];
    }

    #[Test]
    public function get_custom_reward_denormalizes_the_nested_setting_objects(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->rewardPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCustomReward(
            new GetCustomRewardRequest(broadcasterId: '274637212'),
            new StaticAccessToken(),
        );

        $this->assertInstanceOf(CustomRewardResponse::class, $response);
        $this->assertCount(1, $response->data);

        $reward = $response->data[0];
        $this->assertInstanceOf(CustomReward::class, $reward);
        $this->assertSame('92af127c-7326-4483-a52b-b0da0be61c01', $reward->id);
        $this->assertSame(50000, $reward->cost);
        $this->assertSame('#00E5CB', $reward->backgroundColor);

        // The is_*/should_* boolean trap.
        $this->assertTrue($reward->isEnabled);
        $this->assertFalse($reward->isUserInputRequired);
        $this->assertFalse($reward->isPaused);
        $this->assertTrue($reward->isInStock);
        $this->assertFalse($reward->shouldRedemptionsSkipRequestQueue);

        $this->assertInstanceOf(MaxPerStreamSetting::class, $reward->maxPerStreamSetting);
        $this->assertTrue($reward->maxPerStreamSetting->isEnabled);
        $this->assertSame(10, $reward->maxPerStreamSetting->maxPerStream);

        $this->assertInstanceOf(MaxPerUserPerStreamSetting::class, $reward->maxPerUserPerStreamSetting);
        $this->assertSame(2, $reward->maxPerUserPerStreamSetting->maxPerUserPerStream);

        $this->assertInstanceOf(GlobalCooldownSetting::class, $reward->globalCooldownSetting);
        $this->assertSame(300, $reward->globalCooldownSetting->globalCooldownSeconds);

        // Nullables stay null.
        $this->assertNull($reward->redemptionsRedeemedCurrentStream);
        $this->assertNull($reward->image);
        $this->assertNull($reward->cooldownExpiresAt);
    }

    #[Test]
    public function get_custom_reward_repeats_ids_and_keeps_only_manageable_false(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->getCustomReward(
            new GetCustomRewardRequest(
                broadcasterId: '1234',
                ids: ['reward-1', 'reward-2'],
                onlyManageableRewards: false,
            ),
            new StaticAccessToken(),
        );

        $this->assertSame(
            'broadcaster_id=1234&id=reward-1&id=reward-2&only_manageable_rewards=false',
            $http->getLastRequest()->getUri()->getQuery(),
        );
    }

    #[Test]
    public function create_custom_reward_sends_a_normalized_body_and_a_broadcaster_query(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->rewardPayload()],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->createCustomReward(
            new CreateCustomRewardRequest(
                broadcasterId: '274637212',
                reward: new CreateCustomReward(title: 'game analysis 1v1', cost: 50000),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/helix/channel_points/custom_rewards', $request->getUri()->getPath());
        $this->assertSame('broadcaster_id=274637212', $request->getUri()->getQuery());

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('game analysis 1v1', $body['title']);
        $this->assertSame(50000, $body['cost']);

        $this->assertInstanceOf(CustomRewardResponse::class, $response);
        $this->assertInstanceOf(CustomReward::class, $response->data[0]);
    }

    #[Test]
    public function update_custom_reward_patches_with_broadcaster_and_reward_id(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [$this->rewardPayload()],
        ], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateCustomReward(
            new UpdateCustomRewardRequest(
                broadcasterId: '274637212',
                id: '92af127c-7326-4483-a52b-b0da0be61c01',
                reward: new CreateCustomReward(title: 'game analysis 1v1', cost: 50000, isEnabled: false),
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('PATCH', $request->getMethod());
        $this->assertSame(
            'broadcaster_id=274637212&id=92af127c-7326-4483-a52b-b0da0be61c01',
            $request->getUri()->getQuery(),
        );

        $body = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertFalse($body['is_enabled']);
    }

    #[Test]
    public function delete_custom_reward_sends_a_delete_without_a_body(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(204, [], ''));

        $this->buildApi($http)->deleteCustomReward(
            new DeleteCustomRewardRequest(broadcasterId: '1234', id: 'reward-1'),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame('DELETE', $request->getMethod());
        $this->assertSame('broadcaster_id=1234&id=reward-1', $request->getUri()->getQuery());
        $this->assertSame('', (string) $request->getBody());
    }

    #[Test]
    public function get_custom_reward_redemption_unwraps_both_enums(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode([
            'data' => [[
                'broadcaster_id'    => '274637212',
                'broadcaster_login' => 'torpedo09',
                'broadcaster_name'  => 'torpedo09',
                'id'                => '17fa2df1-ad76-4804-bfa5-a40ef63efe63',
                'user_id'           => '274637212',
                'user_login'        => 'torpedo09',
                'user_name'         => 'torpedo09',
                'user_input'        => 'Hello',
                'status'            => 'UNFULFILLED',
                'redeemed_at'       => '2020-07-01T18:37:32Z',
                'reward'            => [
                    'id'     => '92af127c-7326-4483-a52b-b0da0be61c01',
                    'title'  => 'game analysis',
                    'prompt' => '',
                    'cost'   => 50000,
                ],
            ]],
            'pagination' => ['cursor' => 'cursor-1'],
        ], JSON_THROW_ON_ERROR)));

        $response = $this->buildApi($http)->getCustomRewardRedemption(
            new GetCustomRewardRedemptionRequest(
                broadcasterId: '274637212',
                rewardId: '92af127c-7326-4483-a52b-b0da0be61c01',
                status: RedemptionStatus::Unfulfilled,
            ),
            new StaticAccessToken(),
        );

        parse_str($http->getLastRequest()->getUri()->getQuery(), $query);
        $this->assertSame('UNFULFILLED', $query['status']);
        $this->assertSame('OLDEST', $query['sort']);

        $this->assertInstanceOf(CustomRewardRedemptionResponse::class, $response);
        $this->assertSame('cursor-1', $response->pagination?->cursor);

        $redemption = $response->data[0];
        $this->assertInstanceOf(CustomRewardRedemption::class, $redemption);
        $this->assertSame('Hello', $redemption->userInput);
        $this->assertSame('UNFULFILLED', $redemption->status);
        $this->assertInstanceOf(DateTimeInterface::class, $redemption->redeemedAt);

        $this->assertInstanceOf(Reward::class, $redemption->reward);
        $this->assertSame(50000, $redemption->reward->cost);
    }

    #[Test]
    public function update_redemption_status_sends_a_manual_body_and_repeats_ids(): void
    {
        $http = new MockHttpClient();
        $http->addResponse(new Response(200, [], json_encode(['data' => []], JSON_THROW_ON_ERROR)));

        $this->buildApi($http)->updateRedemptionStatus(
            new UpdateRedemptionStatusRequest(
                broadcasterId: '274637212',
                ids: ['redemption-1', 'redemption-2'],
                rewardId: 'reward-1',
                status: RedemptionStatus::Fulfilled,
            ),
            new StaticAccessToken(),
        );

        $request = $http->getLastRequest();
        $this->assertSame(
            'broadcaster_id=274637212&id=redemption-1&id=redemption-2&reward_id=reward-1',
            $request->getUri()->getQuery(),
        );

        // The body is built by hand, not by the normalizer.
        $this->assertSame('{"status":"FULFILLED"}', (string) $request->getBody());
    }
}
