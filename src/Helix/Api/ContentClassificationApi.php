<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use CuyZ\Valinor\Mapper\MappingError;
use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\CCLs\ContentClassificationLabel;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class ContentClassificationApi extends AbstractApi
{
    protected const BASE_PATH = 'content_classification_labels';

    /**
     * Gets information about Twitch content classification labels.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/content_classification_labels
     *
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     * @param string               $locale      Locale for the Content Classification Labels. You may specify a maximum
     *                                          of 1 locale. Default: “en-US” Supported locales: "bg-BG", "cs-CZ",
     *                                          "da-DK", "da-DK", "de-DE", "el-GR",
     *                                          "en-GB", "en-US", "es-ES", "es-MX", "fi-FI", "fr-FR", "hu-HU", "it-IT",
     *                                          "ja-JP", "ko-KR",
     *                                          "nl-NL", "no-NO", "pl-PL", "pt-BT", "pt-PT", "ro-RO", "ru-RU", "sk-SK",
     *                                          "sv-SE", "th-TH",
     *                                          "tr-TR", "vi-VN", "zh-CN", "zh-TW"
     *
     * @return TwitchDataResponse<ContentClassificationLabel[]>
     */
    public function getContentClassificationLevels(
        AccessTokenInterface $accessToken,
        string $locale = 'en-US'
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'locale' => $locale,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ContentClassificationLabel::class),
            accessToken: $accessToken
        );
    }
}
