<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\CreateExtensionSecretRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionBitsProductsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionConfigurationSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionLiveChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionSecretsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\GetReleasedExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SendExtensionChatMessageRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SendExtensionPubSubMessageRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SetExtensionConfigurationSegmentRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\SetExtensionRequiredConfigurationRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Request\UpdateExtensionBitsProductRequest;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionBitsProductsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionConfigurationSegmentsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionLiveChannelsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionSecretsResponse;
use SimplyStream\TwitchApi\Helix\Api\Extensions\Response\ExtensionsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class ExtensionsApi extends AbstractApi
{
    private const string BASE_PATH = 'extensions';
    private const string BITS_PATH = 'bits/extensions';

    /**
     * Gets the specified configuration segment from the specified extension.
     *
     * Rate Limits: You may retrieve each segment a maximum of 20 times per minute.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). The signed JWT must
     * include the role, user_id, and exp fields; role must be set to external.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/configurations
     *
     * @param GetExtensionConfigurationSegmentRequest $request
     * @param AccessTokenInterface                    $accessToken A signed JWT created by an EBS, with role set to
     *                                                             external.
     *
     * @return ExtensionConfigurationSegmentsResponse
     */
    public function getExtensionConfigurationSegment(
        GetExtensionConfigurationSegmentRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionConfigurationSegmentsResponse {
        $query = array_filter(
            [
                'extension_id'   => $request->extensionId,
                'segment'        => $request->segments,
                'broadcaster_id' => $request->broadcasterId,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(
            self::BASE_PATH . '/configurations',
            ExtensionConfigurationSegmentsResponse::class,
            $accessToken,
            $query,
        );
    }

    /**
     * Updates a configuration segment. The segment is limited to 5 KB. Extensions that are active on a channel do not
     * receive the updated configuration.
     *
     * Rate Limits: You may update the configuration a maximum of 20 times per minute.
     *
     * Authorization
     * Requires a signed JWT created by an EBS, with role set to external.
     *
     * URL
     * PUT https://api.twitch.tv/helix/extensions/configurations
     *
     * @param SetExtensionConfigurationSegmentRequest $request
     * @param AccessTokenInterface                    $accessToken A signed JWT created by an EBS, with role set to
     *                                                             external.
     *
     * @return void
     */
    public function setExtensionConfigurationSegment(
        SetExtensionConfigurationSegmentRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->putWithoutResponse(
            self::BASE_PATH . '/configurations',
            $accessToken,
            $this->normalizer->normalize($request),
        );
    }

    /**
     * Updates the extension's required_configuration string. Use this endpoint if your extension requires the
     * broadcaster to configure the extension before activating it.
     *
     * Authorization
     * Requires a signed JWT created by an EBS. Set the role field to external and the user_id field to the ID of the
     * user that owns the extension.
     *
     * URL
     * PUT https://api.twitch.tv/helix/extensions/required_configuration
     *
     * @param SetExtensionRequiredConfigurationRequest $request
     * @param AccessTokenInterface                     $accessToken A signed JWT created by an EBS, with role set to
     *                                                              external and user_id set to the extension owner.
     *
     * @return void
     */
    public function setExtensionRequiredConfiguration(
        SetExtensionRequiredConfigurationRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->putWithoutResponse(
            self::BASE_PATH . '/required_configuration',
            $accessToken,
            $this->normalizer->normalize($request),
            ['broadcaster_id' => $request->broadcasterId],
        );
    }

    /**
     * Sends a message to one or more viewers. You can send messages to a specific channel or to all channels where
     * your extension is active.
     *
     * Rate Limits: You may send a maximum of 100 messages per minute per combination of extension client ID and
     * broadcaster ID.
     *
     * Authorization
     * Requires a signed JWT created by an EBS. The signed JWT must include the role, user_id, and exp fields along
     * with the channel_id and pubsub_perms fields. The role field must be set to external.
     *
     * To send the message to a specific channel, set channel_id in the JWT to the channel's ID and pubsub_perms.send
     * to ["broadcast"]. To send to all channels on which your extension is active, set channel_id to "all" and
     * pubsub_perms.send to ["global"].
     *
     * URL
     * POST https://api.twitch.tv/helix/extensions/pubsub
     *
     * @param SendExtensionPubSubMessageRequest $request
     * @param AccessTokenInterface              $accessToken A signed JWT created by an EBS, with role set to external
     *                                                       and channel_id/pubsub_perms populated.
     *
     * @return void
     */
    public function sendExtensionPubSubMessage(
        SendExtensionPubSubMessageRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/pubsub',
            $accessToken,
            $this->normalizer->normalize($request),
        );
    }

    /**
     * Gets a list of broadcasters that are streaming live and have installed or activated the extension.
     *
     * It may take a few minutes for the list to include or remove broadcasters that have recently gone live or
     * stopped broadcasting.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/live
     *
     * @param GetExtensionLiveChannelsRequest $request
     * @param AccessTokenInterface            $accessToken Requires an app access token or user access token.
     *
     * @return ExtensionLiveChannelsResponse
     */
    public function getExtensionLiveChannels(
        GetExtensionLiveChannelsRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionLiveChannelsResponse {
        $query = array_filter(
            [
                'extension_id' => $request->extensionId,
                'first'        => $request->first,
                'after'        => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/live', ExtensionLiveChannelsResponse::class, $accessToken, $query);
    }

    /**
     * Gets an extension's list of shared secrets.
     *
     * Authorization
     * Requires a signed JWT created by an EBS, with role set to external.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/jwt/secrets
     *
     * @param GetExtensionSecretsRequest $request
     * @param AccessTokenInterface       $accessToken A signed JWT created by an EBS, with role set to external.
     *
     * @return ExtensionSecretsResponse
     */
    public function getExtensionSecrets(
        GetExtensionSecretsRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionSecretsResponse {
        return $this->get(
            self::BASE_PATH . '/jwt/secrets',
            ExtensionSecretsResponse::class,
            $accessToken,
            ['extension_id' => $request->extensionId],
        );
    }

    /**
     * Creates a shared secret used to sign and verify JWT tokens. Creating a new secret removes the current secrets
     * from service. Use this function only when you are ready to use the new secret it returns.
     *
     * Authorization
     * Requires a signed JWT created by an EBS, with role set to external.
     *
     * URL
     * POST https://api.twitch.tv/helix/extensions/jwt/secrets
     *
     * @param CreateExtensionSecretRequest $request
     * @param AccessTokenInterface         $accessToken A signed JWT created by an EBS, with role set to external.
     *
     * @return ExtensionSecretsResponse
     */
    public function createExtensionSecret(
        CreateExtensionSecretRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionSecretsResponse {
        return $this->post(
            self::BASE_PATH . '/jwt/secrets',
            ExtensionSecretsResponse::class,
            $accessToken,
            query: [
                'extension_id' => $request->extensionId,
                'delay'        => $request->delay,
            ],
        );
    }

    /**
     * Sends a message to the specified broadcaster's chat room. The extension's name is used as the username for the
     * message in the chat room. To send a chat message, your extension must enable Chat Capabilities.
     *
     * Rate Limits: You may send a maximum of 12 messages per minute per channel.
     *
     * Authorization
     * Requires a signed JWT created by an EBS. The signed JWT must include the role and user_id fields; role must be
     * set to external.
     *
     * URL
     * POST https://api.twitch.tv/helix/extensions/chat
     *
     * @param SendExtensionChatMessageRequest $request
     * @param AccessTokenInterface            $accessToken A signed JWT created by an EBS, with role set to external.
     *
     * @return void
     */
    public function sendExtensionChatMessage(
        SendExtensionChatMessageRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->postWithoutResponse(
            self::BASE_PATH . '/chat',
            $accessToken,
            $this->normalizer->normalize($request),
            ['broadcaster_id' => $request->broadcasterId],
        );
    }
    /**
     * Gets information about an extension.
     *
     * Authorization
     * Requires a signed JWT created by an EBS, with role set to external.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions
     *
     * @param GetExtensionsRequest $request
     * @param AccessTokenInterface $accessToken A signed JWT created by an EBS, with role set to external.
     *
     * @return ExtensionsResponse
     */
    public function getExtensions(
        GetExtensionsRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionsResponse {
        $query = array_filter(
            [
                'extension_id'      => $request->extensionId,
                'extension_version' => $request->extensionVersion,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH, ExtensionsResponse::class, $accessToken, $query);
    }

    /**
     * Gets information about a released extension. Returns the extension if its state is Released.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/released
     *
     * @param GetReleasedExtensionsRequest $request
     * @param AccessTokenInterface         $accessToken Requires an app access token or user access token.
     *
     * @return ExtensionsResponse
     */
    public function getReleasedExtensions(
        GetReleasedExtensionsRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionsResponse {
        $query = array_filter(
            [
                'extension_id'      => $request->extensionId,
                'extension_version' => $request->extensionVersion,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/released', ExtensionsResponse::class, $accessToken, $query);
    }

    /**
     * Gets the list of Bits products that belongs to the extension. The client ID in the app access token identifies
     * the extension.
     *
     * Authorization
     * Requires an app access token. The client ID in the app access token must be the extension's client ID.
     *
     * URL
     * GET https://api.twitch.tv/helix/bits/extensions
     *
     * @param GetExtensionBitsProductsRequest $request
     * @param AccessTokenInterface            $accessToken Requires an app access token whose client ID is the
     *                                                     extension's client ID.
     *
     * @return ExtensionBitsProductsResponse
     */
    public function getExtensionBitsProducts(
        GetExtensionBitsProductsRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionBitsProductsResponse {
        return $this->get(
            self::BITS_PATH,
            ExtensionBitsProductsResponse::class,
            $accessToken,
            ['should_include_all' => $request->shouldIncludeAll],
        );
    }

    /**
     * Adds or updates a Bits product that the extension created. If the SKU doesn't exist, the product is added. You
     * may update all fields except the sku field.
     *
     * Authorization
     * Requires an app access token. The client ID in the app access token must match the extension's client ID.
     *
     * URL
     * PUT https://api.twitch.tv/helix/bits/extensions
     *
     * @param UpdateExtensionBitsProductRequest $request
     * @param AccessTokenInterface              $accessToken Requires an app access token whose client ID matches the
     *                                                        extension's client ID.
     *
     * @return ExtensionBitsProductsResponse
     */
    public function updateExtensionBitsProduct(
        UpdateExtensionBitsProductRequest $request,
        AccessTokenInterface $accessToken,
    ): ExtensionBitsProductsResponse {
        return $this->put(
            self::BITS_PATH,
            ExtensionBitsProductsResponse::class,
            $accessToken,
            $this->normalizer->normalize($request),
        );
    }
}
