<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\CCLs;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ContentClassificationLabel
{
    use SerializesModels;

    /**
     * @param string $id          Unique identifier for the CCL.
     * @param string $description Localized description of the CCL.
     * @param string $name        Localized name of the CCL.
     */
    public function __construct(
        protected string $id,
        protected string $description,
        protected string $name
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
