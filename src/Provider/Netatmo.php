<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Netatmo extends AbstractProvider
{
    public function getBaseAuthorizationUrl() {
        return 'https://api.netatmo.net/oauth2/authorize';
    }

    public function getBaseAccessTokenUrl() {
        return 'https://api.netatmo.net/oauth2/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token) {
        return 'https://api.netatmo.net/api/getuser?access_token=' . $token;
    }

    public function getDefaultScopes() {
        return ['read_station', 'read_thermostat'];
    }

    public function getScopeSeparator() {
        return ' ';
    }

    public function checkResponse(ResponseInterface $response, $data) {
        // TODO
    }

    public function createResourceOwner(array $response, AccessToken $token)
    {
        var_dump($response);
    }
}
