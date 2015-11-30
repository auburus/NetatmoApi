<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Auburus\OAuth2\Client\Provider\NetatmoResourceOwner;

class Netatmo extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    public function getBaseAuthorizationUrl() {
        return 'https://api.netatmo.net/oauth2/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAccessTokenUrl(array $params) {
        return 'https://api.netatmo.net/oauth2/token';
    }

    /**
     * This funcion only will work if the token is authorized
     * for the read_station scope. 
     * This are the urls depending on the scope.
     * {@inheritdoc}
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token) {
        return 'https://api.netatmo.net/api/getstationsdata?access_token=' . $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScopes() {
        return ['read_station'];
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeSeparator() {
        return ' ';
    }

    /**
     * {@inheritdoc}
     */
    public function checkResponse(ResponseInterface $response, $data) {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createResourceOwner(array $response, AccessToken $token)
    {
        return new NetatmoResourceOwner($response['body']['user']);
    }
}
