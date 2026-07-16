<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

final readonly class Extension
{
    /**
     * @param string   $authorName                The name of the user or organization that owns the extension.
     * @param bool     $bitsEnabled               Whether the extension has features that use Bits.
     * @param bool     $canInstall                Whether a user can install the extension on their channel. Typically
     *                                            false if the extension is in testing mode and requires users to be
     *                                            allowlisted (configured under Extensions -> Extension -> Version ->
     *                                            Access).
     * @param string   $configurationLocation     Where the extension's configuration is stored. Possible values are:
     *                                            - hosted — The Extensions Configuration Service hosts it.
     *                                            - custom — The Extension Backend Service (EBS) hosts it.
     *                                            - none — The extension doesn't require configuration.
     * @param string   $description               A longer description of the extension. It appears on the details
     *                                            page.
     * @param string   $eulaTosUrl                A URL to the extension's Terms of Service.
     * @param bool     $hasChatSupport            Whether the extension can communicate with the installed channel's
     *                                            chat room.
     * @param string   $iconUrl                   A URL to the default icon that's displayed in the Extensions
     *                                            directory.
     * @param array<string, string> $iconUrls     A dictionary that contains URLs to different sizes of the default
     *                                            icon. The key identifies the icon's size (for example, 24x24), the
     *                                            value contains the URL.
     * @param string   $id                        The extension's ID.
     * @param string   $name                      The extension's name.
     * @param string   $privacyPolicyUrl          A URL to the extension's privacy policy.
     * @param bool     $requestIdentityLink       Whether the extension wants to explicitly ask viewers to link their
     *                                            Twitch identity.
     * @param string[] $screenshotUrls            A list of URLs to screenshots that are shown in the Extensions
     *                                            marketplace.
     * @param string   $state                     The extension's state. Possible values are:
     *                                            - Approved
     *                                            - AssetsUploaded
     *                                            - Deleted
     *                                            - Deprecated
     *                                            - InReview
     *                                            - InTest
     *                                            - PendingAction
     *                                            - Rejected
     *                                            - Released
     * @param string   $subscriptionsSupportLevel Whether the extension can view the user's subscription level on the
     *                                            channel it's installed on. Possible values are:
     *                                            - none — The extension can't view the user's subscription level.
     *                                            - optional — The extension can view the user's subscription level.
     * @param string   $summary                   A short description shown when hovering over the discovery splash
     *                                            screen in the Extensions manager.
     * @param string   $supportEmail              The email address that users use to get support for the extension.
     * @param string   $version                   The extension's version number.
     * @param string   $viewerSummary             A brief description displayed on the channel to explain how the
     *                                            extension works.
     * @param array    $views                     Describes all views-related information such as how the extension is
     *                                            displayed on mobile devices.
     * @param string[] $allowlistedConfigUrls     Allowlisted configuration URLs for displaying the extension
     *                                            (configured under Extensions -> Extension -> Version ->
     *                                            Capabilities).
     * @param string[] $allowlistedPanelUrls      Allowlisted panel URLs for displaying the extension (configured
     *                                            under Extensions -> Extension -> Version -> Capabilities).
     */
    public function __construct(
        public string $authorName,
        public bool $bitsEnabled,
        public bool $canInstall,
        public string $configurationLocation,
        public string $description,
        public string $eulaTosUrl,
        public bool $hasChatSupport,
        public string $iconUrl,
        public array $iconUrls,
        public string $id,
        public string $name,
        public string $privacyPolicyUrl,
        public bool $requestIdentityLink,
        public array $screenshotUrls,
        public string $state,
        public string $subscriptionsSupportLevel,
        public string $summary,
        public string $supportEmail,
        public string $version,
        public string $viewerSummary,
        public array $views,
        public array $allowlistedConfigUrls,
        public array $allowlistedPanelUrls,
    ) {
    }
}
