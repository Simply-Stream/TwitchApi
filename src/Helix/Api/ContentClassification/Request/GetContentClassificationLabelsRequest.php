<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ContentClassification\Request;

use SimplyStream\TwitchApi\Helix\Api\ContentClassification\Locale;

final readonly class GetContentClassificationLabelsRequest
{
    /**
     * @param Locale $locale Locale for the Content Classification Labels. You may specify a maximum of 1 locale.
     *                      Default: “en-US”.
     */
    public function __construct(
        public Locale $locale = Locale::EnUs,
    ) {
    }
}
