<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Token\AccessToken;
use Auburus\OAuth2\Client\Provider\Exception\ResourceOwnerException;

class NetatmoHome extends BaseNetatmo
{
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.netatmo.net/api/gethomedata?access_token=' . $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScopes()
    {
        return ['read_camera'];
    }
}
