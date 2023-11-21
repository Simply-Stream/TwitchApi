<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Authentication\Grant;

use League\OAuth2\Client\Grant\AbstractGrant;

class Oidc extends AbstractGrant
{
    /**
     * @inheritDoc
     */
    protected function getName()
    {
        return 'authorization_code';
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredRequestParameters()
    {
        return ['code'];
    }
}
