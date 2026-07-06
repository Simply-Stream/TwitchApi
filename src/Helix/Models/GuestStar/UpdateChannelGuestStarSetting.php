<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\GuestStar;

use Webmozart\Assert\Assert;

final readonly class UpdateChannelGuestStarSetting
{
    private const array VALID_GROUP_LAYOUTS = [
        'TILED_LAYOUT',
        'SCREENSHARE_LAYOUT',
        'HORIZONTAL_LAYOUT',
        'VERTICAL_LAYOUT',
    ];

    /**
     * @param bool|null   $isModeratorSendLiveEnabled  Flag determining if Guest Star moderators have access to control
     *                                                 whether a guest is live once assigned to a slot.
     * @param int|null    $slotCount                   Number of slots the Guest Star call interface will allow the
     *                                                 host to add to a call. Required to be between 1 and 6.
     * @param bool|null   $isBrowserSourceAudioEnabled Flag determining if Browser Sources subscribed to sessions on
     *                                                 this channel should output audio
     * @param string|null $groupLayout                 This setting determines how the guests within a session should
     *                                                 be laid out within the browser source. Can be one of the
     *                                                 following values:
     *                                                 - TILED_LAYOUT
     *                                                 - SCREENSHARE_LAYOUT
     *                                                 - HORIZONTAL_LAYOUT
     *                                                 - VERTICAL_LAYOUT
     * @param bool|null   $regenerateBrowserSources    Flag determining if Guest Star should regenerate the auth token
     *                                                 associated with the channel’s browser sources. Providing a true
     *                                                 value for this will immediately invalidate all browser sources
     *                                                 previously configured in your streaming software.
     */
    public function __construct(
        public ?bool $isModeratorSendLiveEnabled = null,
        public ?int $slotCount = null,
        public ?bool $isBrowserSourceAudioEnabled = null,
        public ?string $groupLayout = null,
        public ?bool $regenerateBrowserSources = null,
    ) {
        if (null !== $this->slotCount) {
            Assert::greaterThanEq($this->slotCount, 1, 'Slot count needs to be at least 1, got %s');
            Assert::lessThanEq($this->slotCount, 6, 'Slot count should be less than or equal to 6, got %s');
        }

        if (null !== $this->groupLayout) {
            Assert::inArray(
                $this->groupLayout,
                self::VALID_GROUP_LAYOUTS,
                'Group layout got an invalid value. Allowed values are: TILED_LAYOUT, SCREENSHARE_LAYOUT, HORIZONTAL_LAYOUT, VERTICAL_LAYOUT. Got %s',
            );
        }
    }
}
