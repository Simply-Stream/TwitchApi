<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Helix\Models\ChannelPoints;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CreateCustomRewardRequest;
use Webmozart\Assert\InvalidArgumentException;

class CreateCustomRewardRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $title = "Test Reward";
        $cost = 1000;
        $prompt = "Test Prompt";
        $isEnabled = true;
        $backgroundColor = "#FFFFFF";
        $isUserInputRequired = false;
        $isMaxPerStreamEnabled = false;
        $maxPerStream = null;
        $isMaxPerUserPerStreamEnabled = false;
        $maxPerUserPerStream = null;
        $isGlobalCooldownEnabled = false;
        $globalCooldownSeconds = null;
        $shouldRedemptionsSkipRequestQueue = false;

        $createCustomRewardRequest = new CreateCustomRewardRequest(
            $title,
            $cost,
            $prompt,
            $isEnabled,
            $backgroundColor,
            $isUserInputRequired,
            $isMaxPerStreamEnabled,
            $maxPerStream,
            $isMaxPerUserPerStreamEnabled,
            $maxPerUserPerStream,
            $isGlobalCooldownEnabled,
            $globalCooldownSeconds,
            $shouldRedemptionsSkipRequestQueue
        );

        $this->assertEquals($title, $createCustomRewardRequest->getTitle());
        $this->assertEquals($cost, $createCustomRewardRequest->getCost());
        $this->assertEquals($prompt, $createCustomRewardRequest->getPrompt());
        $this->assertEquals($isEnabled, $createCustomRewardRequest->isEnabled());
        $this->assertEquals($backgroundColor, $createCustomRewardRequest->getBackgroundColor());
        $this->assertEquals($isUserInputRequired, $createCustomRewardRequest->isUserInputRequired());
        $this->assertEquals($isMaxPerStreamEnabled, $createCustomRewardRequest->isMaxPerStreamEnabled());
        $this->assertEquals($maxPerStream, $createCustomRewardRequest->getMaxPerStream());
        $this->assertEquals($isMaxPerUserPerStreamEnabled, $createCustomRewardRequest->isMaxPerUserPerStreamEnabled());
        $this->assertEquals($maxPerUserPerStream, $createCustomRewardRequest->getMaxPerUserPerStream());
        $this->assertEquals($isGlobalCooldownEnabled, $createCustomRewardRequest->isGlobalCooldownEnabled());
        $this->assertEquals($globalCooldownSeconds, $createCustomRewardRequest->getGlobalCooldownSeconds());
        $this->assertEquals(
            $shouldRedemptionsSkipRequestQueue,
            $createCustomRewardRequest->isShouldRedemptionsSkipRequestQueue()
        );

        $this->assertIsArray($createCustomRewardRequest->toArray());
        $this->assertSame([
            'title' => $title,
            'cost' => $cost,
            'prompt' => $prompt,
            'is_enabled' => $isEnabled,
            'background_color' => $backgroundColor,
            'is_user_input_required' => $isUserInputRequired,
            'is_max_per_stream_enabled' => $isMaxPerStreamEnabled,
            'max_per_stream' => $maxPerStream,
            'is_max_per_user_per_stream_enabled' => $isMaxPerUserPerStreamEnabled,
            'max_per_user_per_stream' => $maxPerUserPerStream,
            'is_global_cooldown_enabled' => $isGlobalCooldownEnabled,
            'global_cooldown_seconds' => $globalCooldownSeconds,
            'should_redemptions_skip_request_queue' => $shouldRedemptionsSkipRequestQueue,
        ], $createCustomRewardRequest->toArray());
    }

    public function testTitleCanNotBeEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The title can't be an empty string");

        $title = '';
        $cost = 1000;

        new CreateCustomRewardRequest(
            $title,
            $cost,
        );
    }

    public function testTitleIsTooLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The title may contain a maximum of 45 characters. Got "46" characters.');

        $title = str_repeat('a', 46);
        $cost = 1000;

        new CreateCustomRewardRequest(
            $title,
            $cost,
        );
    }

    public function testCostCantBeLowerThanOne()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum cost is 1 point. Got "-1"');

        $title = 'Test Reward';
        $cost = -1;

        new CreateCustomRewardRequest(
            $title,
            $cost,
        );
    }

    public function testPromptCantBeEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The prompt can't be empty, when user input is required");

        $title = 'Test Reward';
        $cost = 100;
        $prompt = '';

        new CreateCustomRewardRequest(
            $title,
            $cost,
            $prompt,
            isUserInputRequired: true
        );
    }

    public function testPromptCantBeTooLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The prompt is limited to a maximum of 200 characters. Got "201" characters.');

        $title = 'Test Reward';
        $cost = 100;
        $prompt = str_repeat('a', 201);

        new CreateCustomRewardRequest(
            $title,
            $cost,
            $prompt,
            isUserInputRequired: true
        );
    }

    public function testBackgroundColorHasToBeValidHex()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The given background color "#GFFFFF" is not a valid hex format. Valid formats "#9147FF", "#FFF"');

        $title = 'Test Reward';
        $cost = 1000;
        $backgroundColor = '#GFFFFF';

        new CreateCustomRewardRequest(
            $title,
            $cost,
            backgroundColor: $backgroundColor
        );
    }

    public function testMaxPerStreamMustBeGreaterThanOne()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum value of maxPerStream is 1. Got "-1"');

        $title = 'Test Reward';
        $cost = 1000;

        new CreateCustomRewardRequest(
            $title,
            $cost,
            maxPerStream: -1,
            isMaxPerStreamEnabled: true
        );
    }

    public function testMaxPerUserPerStreamMustBeGreaterThanOne()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum value of maxPerUserPerStream is 1. Got "-1"');

        $title = 'Test Reward';
        $cost = 1000;

        new CreateCustomRewardRequest(
            $title,
            $cost,
            maxPerUserPerStream: -1,
            isMaxPerUserPerStreamEnabled: true
        );
    }

    public function testGlobalCooldownSecondsMustBeGreaterThanOne()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum value of globalCooldownSeconds is 1. Got "-1". However, the minimum value is 60 to be shown in Twitch UI');

        $title = 'Test Reward';
        $cost = 1000;

        new CreateCustomRewardRequest(
            $title,
            $cost,
            globalCooldownSeconds: -1,
            isGlobalCooldownEnabled: true
        );
    }
}
