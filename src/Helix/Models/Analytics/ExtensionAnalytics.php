<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Analytics;

use SimplyStream\TwitchApi\Helix\Api\DateRange;

final readonly class ExtensionAnalytics
{
    /**
     * @param string    $extensionId An ID that identifies the extension that the report was generated for.
     * @param string    $url         The URL that you use to download the report. The URL is valid for 5 minutes.
     * @param string    $type        The type of report.
     * @param DateRange $dateRange   The reporting window’s start and end dates, in RFC3339 format.
     */
    public function __construct(
        public string $extensionId,
        public string $url,
        public string $type,
        public DateRange $dateRange,
    ) {
    }
}
