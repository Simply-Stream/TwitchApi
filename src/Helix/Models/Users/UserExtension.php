<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

final readonly class UserExtension
{
    /**
     * @param string       $id          An ID that identifies the extension.
     * @param string       $version     The extension’s version.
     * @param string       $name        The extension’s name.
     * @param bool         $canActivate A Boolean value that determines whether the extension is configured and can be
     *                                  activated.
     * @param list<string> $type        The extension types that you can activate for this extension. Possible values
     *                                  are:
     *                                  - component
     *                                  - mobile
     *                                  - overlay
     *                                  - panel
     */
    public function __construct(
        public string $id,
        public string $version,
        public string $name,
        public bool $canActivate,
        public array $type,
    ) {
    }
}
