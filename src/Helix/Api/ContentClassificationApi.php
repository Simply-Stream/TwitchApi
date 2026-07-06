<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Request\GetContentClassificationLabelsRequest;
use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Response\ContentClassificationLabelsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class ContentClassificationApi extends AbstractApi
{
    private const string BASE_PATH = 'content_classification_labels';

    /**
     * Gets information about Twitch content classification labels.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/content_classification_labels
     *
     * @param GetContentClassificationLabelsRequest $request
     * @param AccessTokenInterface                  $accessToken Requires an app access token or user access token.
     *
     * @return ContentClassificationLabelsResponse
     */
    public function getContentClassificationLabels(
        GetContentClassificationLabelsRequest $request,
        AccessTokenInterface $accessToken,
    ): ContentClassificationLabelsResponse {
        return $this->get(
            self::BASE_PATH,
            ContentClassificationLabelsResponse::class,
            $accessToken,
            [
                'locale' => $request->locale->value,
            ],
        );
    }
}
