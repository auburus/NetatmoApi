<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Token\AccessToken;
use Auburus\OAuth2\Client\Provider\Exception\ResourceOwnerException;

class Netatmo extends BaseNetatmo
{
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        throw new ResourceOwnerException(
            'Use one of the specific subclasses ' .
            'to retrieve the resource owner'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScopes()
    {
        return [];
    }
}
