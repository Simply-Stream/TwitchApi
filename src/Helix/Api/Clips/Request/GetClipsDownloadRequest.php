<?php

namespace SimplyStream\TwitchApi\Helix\Api\Clips\Request;

final readonly class GetClipsDownloadRequest {
    /**
     * @param string $editorId The User ID of the editor for the channel you want to download a clip for. If using the broadcaster’s auth token, this is the same as broadcaster_id. This must match the user_id in the user access token.
     * @param string $broadcasterId The ID of the broadcaster you want to download clips for.
     * @param array  $clipIds The ID that identifies the clip you want to download. Include this parameter for each clip you want to download, up to a maximum of 10 clips. For example, clip_id=SleepyGiftedPeppermintNerfRedBlaster-KbkBXYt3lOk3jy8-&clip_id=WimpyAltruisticKleeKeyboardCat-EiY5yMrEwZ4i4gwC.
     */
    public function __construct(
        public string $editorId,
        public string $broadcasterId,
        public array $clipIds,
    ) { }
}
