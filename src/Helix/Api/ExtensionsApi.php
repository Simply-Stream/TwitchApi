<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Extensions\Extension;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionBitsProduct;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionConfigurationSegment;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionLiveChannel;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionSecret;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SendExtensionChatMessageRequest;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SendExtensionPubSubMessageRequest;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SetExtensionConfigurationSegmentRequest;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SetExtensionRequiredConfigurationRequest;
use SimplyStream\TwitchApi\Helix\Models\Extensions\UpdateExtensionBitsProductRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class ExtensionsApi extends AbstractApi
{
    protected const BASE_PATH = 'extensions';

    /**
     * Gets the specified configuration segment from the specified extension.
     *
     * Rate Limits: You may retrieve each segment a maximum of 20 times per minute.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). For signing requirements,
     * see Signing the JWT. The signed JWT must include the role, user_id, and exp fields (see JWT Schema). The role
     * field must be set to external.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/configurations
     *
     * @param string      $extensionId   The ID of the extension that contains the configuration segment you want to
     *                                   get.
     * @param string      $segment       The type of configuration segment to get. Possible case-sensitive values are:
     *                                   - broadcaster
     *                                   - developer
     *                                   - global
     *
     *                                   You may specify one or more segments. To specify multiple segments, include
     *                                   the segment parameter for each segment to get. For example,
     *                                   segment=broadcaster&segment=developer. Ignores duplicate segments.
     * @param string      $jwt           Requires a signed JSON Web Token (JWT) created by an Extension Backend Service
     *                                   (EBS). For signing requirements, see Signing the JWT. The signed JWT must
     *                                   include the role, user_id, and exp fields (see JWT Schema). The role field
     *                                   must be set to external.
     * @param string|null $broadcasterId The ID of the broadcaster that installed the extension. This parameter is
     *                                   required if you set the segment parameter to broadcaster or developer. Do not
     *                                   specify this parameter if you set segment to global.
     *
     * @return TwitchDataResponse<ExtensionConfigurationSegment[]>
     */
    public function getExtensionConfigurationSegment(
        string $extensionId,
        string $segment,
        string $jwt,
        string $broadcasterId = null
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/configurations',
            query: [
                'extension_id' => $extensionId,
                'segment' => $segment,
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ExtensionConfigurationSegment::class),
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ]
        );
    }

    /**
     * Updates a configuration segment. The segment is limited to 5 KB. Extensions that are active on a channel do not
     * receive the updated configuration.
     *
     * Rate Limits: You may update the configuration a maximum of 20 times per minute.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). For signing requirements,
     * see Signing the JWT. The signed JWT must include the role, user_id, and exp fields (see JWT Schema). The role
     * field must be set to external.
     *
     * URL
     * PUT https://api.twitch.tv/helix/extensions/configurations
     *
     * @param string                                  $jwt Requires a signed JSON Web Token (JWT) created by an
     *                                                     Extension Backend Service (EBS). For signing requirements,
     *                                                     see Signing the JWT. The signed JWT must include the role,
     *                                                     user_id, and exp fields (see JWT Schema). The role field
     *                                                     must be set to external.
     * @param SetExtensionConfigurationSegmentRequest $body
     *
     * @return void
     */
    public function setExtensionConfigurationSegment(
        string $jwt,
        SetExtensionConfigurationSegmentRequest $body
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/configurations',
            method: 'PUT',
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ],
            body: $body
        );
    }

    /**
     * Updates the extension’s required_configuration string. Use this endpoint if your extension requires the
     * broadcaster to configure the extension before activating it (to require configuration, you must select Custom/My
     * Own Service in Extension Capabilities). For more information, see Required Configurations and Setting Required
     * Configuration.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an EBS. For signing requirements, see Signing the JWT. The
     * signed JWT must include the role, user_id, and exp fields (see JWT Schema). Set the role field to external and
     * the user_id field to the ID of the user that owns the extension.
     *
     * URL
     * PUT https://api.twitch.tv/helix/extensions/required_configuration
     *
     * @param string                                   $broadcasterId The ID of the broadcaster that installed the
     *                                                                extension on their channel.
     * @param SetExtensionRequiredConfigurationRequest $body
     * @param string                                   $jwt           Requires a signed JSON Web Token (JWT) created by
     *                                                                an EBS. For signing requirements, see Signing the
     *                                                                JWT. The signed JWT must include the role,
     *                                                                user_id, and exp fields (see JWT Schema). Set the
     *                                                                role field to external and the user_id field to
     *                                                                the ID of the user that owns the extension.
     *
     * @return void
     */
    public function setExtensionRequiredConfiguration(
        string $broadcasterId,
        SetExtensionRequiredConfigurationRequest $body,
        string $jwt
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/required_configuration',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            method: 'PUT',
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ],
            body: $body
        );
    }

    /**
     * Sends a message to one or more viewers. You can send messages to a specific channel or to all channels where
     * your extension is active. This endpoint uses the same mechanism as the send JavaScript helper function used to
     * send messages.
     *
     * Rate Limits: You may send a maximum of 100 messages per minute per combination of extension client ID and
     * broadcaster ID.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). For signing requirements,
     * see Signing the JWT. The signed JWT must include the role, user_id, and exp fields (see JWT Schema) along with
     * the channel_id and pubsub_perms fields. The role field must be set to external.
     *
     * To send the message to a specific channel, set the channel_id field in the JWT to the channel’s ID and set the
     * pubsub_perms.send array to broadcast.
     *
     * {
     *      'exp': 1503343947,
     *      'user_id': '27419011',
     *      'role': 'external',
     *      'channel_id': '27419011',
     *      'pubsub_perms': {
     *          'send':[
     *              'broadcast'
     *          ]
     *      }
     * }
     *
     * To send the message to all channels on which your extension is active, set the channel_id field to all and set
     * the pubsub_perms.send array to global.
     *
     * {
     *      'exp': 1503343947,
     *      'user_id': '27419011',
     *      'role': 'external',
     *      'channel_id': 'all',
     *      'pubsub_perms': {
     *          'send':[
     *              'global'
     *          ]
     *      }
     * }
     *
     * URL
     * POST https://api.twitch.tv/helix/extensions/pubsub
     *
     * @param SendExtensionPubSubMessageRequest $body
     * @param string                            $jwt Requires a signed JSON Web Token (JWT) created by an Extension
     *                                               Backend Service (EBS). For signing requirements, see Signing the
     *                                               JWT. The signed JWT must include the role, user_id, and exp fields
     *                                               (see JWT Schema) along with the channel_id and pubsub_perms
     *                                               fields. The role field must be set to external.
     *
     * @return void
     */
    public function sendExtensionPubSubMessage(
        SendExtensionPubSubMessageRequest $body,
        string $jwt
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/pubsub',
            method: 'POST',
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ],
            body: $body
        );
    }

    /**
     * Gets a list of broadcasters that are streaming live and have installed or activated the extension.
     *
     * It may take a few minutes for the list to include or remove broadcasters that have recently gone live or stopped
     * broadcasting.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/live
     *
     * @param string               $extensionId      The ID of the extension to get. Returns the list of broadcasters
     *                                               that are live and that have installed or activated this extension.
     * @param AccessTokenInterface $accessToken      Requires an app access token or user access token.
     * @param int                  $first            The maximum number of items to return per page in the response.
     *                                               The minimum page size is 1 item per page and the maximum is 100
     *                                               items per page. The default is 20.
     * @param string|null          $after            The cursor used to get the next page of results. The pagination
     *                                               field in the response contains the cursor’s value.
     *
     * @return TwitchPaginatedDataResponse<ExtensionLiveChannel>
     */
    public function getExtensionLiveChannels(
        string $extensionId,
        AccessTokenInterface $accessToken,
        int $first = 20,
        string $after = null,
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/live',
            query: [
                'extension_id' => $extensionId,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, ExtensionLiveChannel::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets an extension’s list of shared secrets.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). For signing requirements,
     * see Signing the JWT. The signed JWT must include the role, user_id, and exp fields (see JWT Schema). The role
     * field must be set to external.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions/jwt/secrets
     *
     * @param string $extensionId The ID of the extension whose shared secrets you want to get.
     * @param string $jwt         Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS).
     *                            For signing requirements, see Signing the JWT. The signed JWT must include the role,
     *                            user_id, and exp fields (see JWT Schema). The role field must be set to external.
     *
     * @return TwitchDataResponse<ExtensionSecret>
     */
    public function getExtensionSecrets(
        string $extensionId,
        string $jwt
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/jwt/secrets',
            query: [
                'extension_id' => $extensionId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ExtensionSecret::class),
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ]
        );
    }

    /**
     * Creates a shared secret used to sign and verify JWT tokens. Creating a new secret removes the current secrets
     * from service. Use this function only when you are ready to use the new secret it returns.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). For signing requirements,
     * see Signing the JWT. The signed JWT must include the role, user_id, and exp fields (see JWT Schema). The role
     * field must be set to external.
     *
     * URL
     * POST https://api.twitch.tv/helix/extensions/jwt/secrets
     *
     * @param string $extensionId The ID of the extension to apply the shared secret to.
     * @param string $jwt         Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS).
     *                            For signing requirements, see Signing the JWT. The signed JWT must include the role,
     *                            user_id, and exp fields (see JWT Schema). The role field must be set to external.
     * @param int    $delay       The amount of time, in seconds, to delay activating the secret. The delay should
     *                            provide enough time for instances of the extension to gracefully switch over to the
     *                            new secret. The minimum delay is 300 seconds
     *                            (5 minutes). The default is 300 seconds.
     *
     * @return TwitchDataResponse<ExtensionSecret>
     */
    public function createExtensionSecret(
        string $extensionId,
        string $jwt,
        int $delay = 300
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/jwt/secrets',
            query: [
                'extension_id' => $extensionId,
                'delay' => $delay,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ExtensionSecret::class),
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ]
        );
    }

    /**
     * Sends a message to the specified broadcaster’s chat room. The extension’s name is used as the username for the
     * message in the chat room. To send a chat message, your extension must enable Chat Capabilities (under your
     * extension’s Capabilities tab).
     *
     * Rate Limits: You may send a maximum of 12 messages per minute per channel.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). For signing requirements,
     * see Signing the JWT. The signed JWT must include the role and user_id fields (see JWT Schema). The role field
     * must be set to external.
     *
     * URL
     * POST https://api.twitch.tv/helix/extensions/chat
     *
     * @param string                          $broadcasterId The ID of the broadcaster that has activated the
     *                                                       extension.
     * @param string                          $jwt           Requires a signed JSON Web Token (JWT) created by an
     *                                                       Extension Backend Service (EBS). For signing requirements,
     *                                                       see Signing the JWT. The signed JWT must include the role
     *                                                       and user_id fields (see JWT Schema). The role field must
     *                                                       be set to external.
     * @param SendExtensionChatMessageRequest $body
     *
     * @return void
     */
    public function sendExtensionChatMessage(
        string $broadcasterId,
        string $jwt,
        SendExtensionChatMessageRequest $body
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/chat',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ],
            body: $body
        );
    }

    /**
     * Gets information about an extension.
     *
     * Authorization
     * Requires a signed JSON Web Token (JWT) created by an Extension Backend Service (EBS). For signing requirements,
     * see Signing the JWT. The signed JWT must include the role field (see JWT Schema), and the role field must be set
     * to external.
     *
     * URL
     * GET https://api.twitch.tv/helix/extensions
     *
     * @param string      $extensionId      The ID of the extension to get.
     * @param string      $jwt              Requires a signed JSON Web Token (JWT) created by an Extension Backend
     *                                      Service (EBS). For signing requirements, see Signing the JWT. The signed
     *                                      JWT must include the role field (see JWT Schema), and the role field must
     *                                      be set to external.
     * @param string|null $extensionVersion The version of the extension to get. If not specified, it returns the
     *                                      latest, released version. If you don’t have a released version, you must
     *                                      specify a version; otherwise, the list is empty.
     *
     * @return TwitchDataResponse<Extension[]>
     */
    public function getExtensions(
        string $extensionId,
        string $jwt,
        string $extensionVersion = null
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'extension_id' => $extensionId,
                'extension_version' => $extensionVersion,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Extension::class),
            headers: [
                'Authorization' => 'Bearer ' . $jwt,
            ]
        );
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
     * @param string               $extensionId           The ID of the extension to get.
     * @param AccessTokenInterface $accessToken           Requires an app access token or user access token.
     * @param string|null          $extensionVersion      The version of the extension to get. If not specified, it
     *                                                    returns the latest version.
     *
     * @return TwitchDataResponse<Extension>
     */
    public function getReleasedExtensions(
        string $extensionId,
        AccessTokenInterface $accessToken,
        string $extensionVersion = null,
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/released',
            query: [
                'extension_id' => $extensionId,
                'extension_version' => $extensionVersion,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Extension::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets the list of Bits products that belongs to the extension. The client ID in the app access token identifies
     * the extension.
     *
     * Authorization
     * Requires an app access token. The client ID in the app access token must be the extension’s client ID.
     *
     * URL
     * GET https://api.twitch.tv/helix/bits/extensions
     *
     * @param bool                 $shouldIncludeAll      A Boolean value that determines whether to include disabled
     *                                                    or expired Bits products in the response. The default is
     *                                                    false.
     * @param AccessTokenInterface $accessToken           Requires an app access token. The client ID in the app access
     *                                                    token must be the extension’s client ID.
     *
     * @return TwitchDataResponse<ExtensionBitsProduct[]>
     */
    public function getExtensionBitsProducts(
        AccessTokenInterface $accessToken,
        bool $shouldIncludeAll = false,
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: 'bits/' . self::BASE_PATH,
            query: [
                'should_include_all' => $shouldIncludeAll,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ExtensionBitsProduct::class),
            accessToken: $accessToken
        );
    }

    /**
     * Adds or updates a Bits product that the extension created. If the SKU doesn’t exist, the product is added. You
     * may update all fields except the sku field.
     *
     * Authorization
     * Requires an app access token. The client ID in the app access token must match the extension’s client ID.
     *
     * URL
     * PUT https://api.twitch.tv/helix/bits/extensions
     *
     * @param UpdateExtensionBitsProductRequest $body
     * @param AccessTokenInterface              $accessToken Requires an app access token. The client ID in the app
     *                                                       access token must match the extension’s client ID.
     *
     * @return TwitchDataResponse<ExtensionBitsProduct[]>
     */
    public function updateExtensionBitsProduct(
        UpdateExtensionBitsProductRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: 'bits/' . self::BASE_PATH,
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ExtensionBitsProduct::class),
            method: 'PUT',
            body: $body,
            accessToken: $accessToken
        );
    }
}
