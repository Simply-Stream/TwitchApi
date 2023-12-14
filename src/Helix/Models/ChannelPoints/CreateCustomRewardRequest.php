<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\ChannelPoints;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class CreateCustomRewardRequest extends AbstractModel
{
    /**
     * @param string            $title                             The custom reward’s title. The title may contain a
     *                                                             maximum of 45 characters and it must be unique
     *                                                             amongst all of the broadcaster’s custom rewards.
     * @param positive-int      $cost                              The cost of the reward, in Channel Points. The
     *                                                             minimum is 1 point.
     * @param string|null       $prompt                            The prompt shown to the viewer when they redeem the
     *                                                             reward. Specify a prompt if is_user_input_required
     *                                                             is true. The prompt is limited to a maximum of 200
     *                                                             characters.
     * @param bool              $isEnabled                         A Boolean value that determines whether the reward
     *                                                             is enabled. Viewers see only enabled rewards. The
     *                                                             default is true.
     * @param string|null       $backgroundColor                   The background color to use for the reward. Specify
     *                                                             the color using Hex format (for example, #9147FF).
     * @param bool              $isUserInputRequired               A Boolean value that determines whether the user
     *                                                             needs to enter information when redeeming the
     *                                                             reward. See the prompt field. The default is false.
     * @param bool              $isMaxPerStreamEnabled             A Boolean value that determines whether to limit the
     *                                                             maximum number of redemptions allowed per live
     *                                                             stream (see the max_per_stream field). The default
     *                                                             is false.
     * @param positive-int|null $maxPerStream                      The maximum number of redemptions allowed per live
     *                                                             stream. Applied only if is_max_per_stream_enabled is
     *                                                             true. The minimum value is 1.
     * @param bool              $isMaxPerUserPerStreamEnabled      A Boolean value that determines whether to limit the
     *                                                             maximum number of redemptions allowed per user per
     *                                                             stream (see the max_per_user_per_stream field). The
     *                                                             default is false.
     * @param positive-int|null $maxPerUserPerStream               The maximum number of redemptions allowed per user
     *                                                             per stream. Applied only if
     *                                                             is_max_per_user_per_stream_enabled is true. The
     *                                                             minimum value is 1.
     * @param bool              $isGlobalCooldownEnabled           A Boolean value that determines whether to apply a
     *                                                             cooldown period between redemptions (see the
     *                                                             global_cooldown_seconds field for the duration of
     *                                                             the cooldown period). The default is false.
     * @param positive-int|null $globalCooldownSeconds             The cooldown period, in seconds. Applied only if the
     *                                                             is_global_cooldown_enabled field is true. The
     *                                                             minimum value is 1; however, the minimum value is 60
     *                                                             for it to be shown in the Twitch UX.
     * @param bool              $shouldRedemptionsSkipRequestQueue A Boolean value that determines whether redemptions
     *                                                             should be set to FULFILLED status immediately when a
     *                                                             reward is redeemed. If false, status is set to
     *                                                             UNFULFILLED and follows the normal request queue
     *                                                             process. The default is false.
     */
    public function __construct(
        private string $title,
        private int $cost,
        private ?string $prompt = null,
        private bool $isEnabled = true,
        private ?string $backgroundColor = null,
        private bool $isUserInputRequired = false,
        private bool $isMaxPerStreamEnabled = false,
        private ?int $maxPerStream = null,
        private bool $isMaxPerUserPerStreamEnabled = false,
        private ?int $maxPerUserPerStream = null,
        private bool $isGlobalCooldownEnabled = false,
        private ?int $globalCooldownSeconds = null,
        private bool $shouldRedemptionsSkipRequestQueue = false
    ) {
        Assert::stringNotEmpty($this->title, 'The title can\'t be an empty string');
        Assert::maxLength(
            $this->title,
            45,
            sprintf('The title may contain a maximum of 45 characters. Got "%s" characters.', strlen($this->title))
        );

        Assert::greaterThanEq($this->cost, 1, sprintf('The minimum cost is 1 point. Got "%s".', $this->cost));

        if ($this->isUserInputRequired) {
            Assert::stringNotEmpty($this->prompt, 'The prompt can\'t be empty, when user input is required');
            Assert::maxLength(
                $this->prompt,
                200,
                sprintf(
                    'The prompt is limited to a maximum of 200 characters. Got "%s" characters.',
                    strlen($this->prompt)
                )
            );
        }

        if (null !== $this->backgroundColor) {
            Assert::regex(
                $this->backgroundColor,
                '/^#(?:[0-9a-fA-F]{6}|[0-9a-fA-F]{3})$/',
                sprintf(
                    'The given background color "%s" is not a valid hex format. Valid formats "#9147FF", "#FFF"',
                    $this->backgroundColor
                )
            );
        }

        if ($this->isMaxPerStreamEnabled) {
            Assert::greaterThanEq(
                $this->maxPerStream,
                1,
                sprintf('The minimum value of maxPerStream is 1. Got "%s"', $this->maxPerStream)
            );
        }

        if ($this->isMaxPerUserPerStreamEnabled) {
            Assert::greaterThanEq(
                $this->maxPerUserPerStream,
                1,
                sprintf('The minimum value of maxPerUserPerStream is 1. Got "%s"', $this->maxPerUserPerStream)
            );
        }

        if ($this->isGlobalCooldownEnabled) {
            Assert::greaterThanEq(
                $this->globalCooldownSeconds,
                1,
                sprintf(
                    'The minimum value of globalCooldownSeconds is 1. Got "%s". However, the minimum value is 60 to be shown in Twitch UI',
                    $this->globalCooldownSeconds
                )
            );
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function isUserInputRequired(): bool
    {
        return $this->isUserInputRequired;
    }

    public function isMaxPerStreamEnabled(): bool
    {
        return $this->isMaxPerStreamEnabled;
    }

    public function getMaxPerStream(): ?int
    {
        return $this->maxPerStream;
    }

    public function isMaxPerUserPerStreamEnabled(): bool
    {
        return $this->isMaxPerUserPerStreamEnabled;
    }

    public function getMaxPerUserPerStream(): ?int
    {
        return $this->maxPerUserPerStream;
    }

    public function isGlobalCooldownEnabled(): bool
    {
        return $this->isGlobalCooldownEnabled;
    }

    public function getGlobalCooldownSeconds(): ?int
    {
        return $this->globalCooldownSeconds;
    }

    public function isShouldRedemptionsSkipRequestQueue(): bool
    {
        return $this->shouldRedemptionsSkipRequestQueue;
    }
}
