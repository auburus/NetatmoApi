<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Entity\User;

class Netatmo extends AbstractProvider
{
    public function urlAuthorize() {
        return 'https://api.netatmo.net/oauth2/authorize';
    }

    public function urlAccessToken() {
        return 'https://api.netatmo.net/oauth2/token';
    }

    public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token) {
        return 'https://api.netatmo.net/api/getuser?access_token=' . $token;
    }

    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return $response;
    }
}
