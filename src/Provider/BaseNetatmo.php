<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Auburus\OAuth2\Client\Provider\NetatmoResourceOwner;
use Auburus\OAuth2\Client\Provider\Exception\ResourceOwnerException;

abstract class BaseNetatmo extends AbstractProvider
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
