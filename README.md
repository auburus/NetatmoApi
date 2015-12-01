# NetatmoApi

This is a Provider implementation for the [league/OAuth2-client](https://github.com/thephpleague/oauth2-client) library.

## Install

Via Composer

``` bash
$ composer require auburus/netatmo-api:~0.2.0
```

## Usage

Here's a code based on the [usage example](https://github.com/thephpleague/oauth2-client/blob/master/README.md#usage) example.

``` php
<?php

require_once 'vendor/autoload.php';

use Auburus\OAuth2\Client\Provider\Netatmo;
use GuzzleHttp\Exception\RequestException;

session_start();

$provider = new Netatmo([
    'clientId'      => 'XXXXXXXX',
    'clientSecret'  => 'XXXXXXXX',
    'redirectUri'   => 'https://your-registered-redirect-uri/',
]);

// Handles the case when the user choose to NOT authorize
if (isset($_GET['error'])) {
    echo $_GET['error'];
    exit;
}

if (!isset($_GET['code'])) {


    $authorizationUrl = $provider->getAuthorizationUrl([
        'scope' => ['read_station']
    ]);

    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo $accessToken->getToken() . "<br>";
        echo $accessToken->getRefreshToken() . "<br>";
        echo $accessToken->getExpires() . "<br>";
        echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        echo '<br>';

        var_dump($resourceOwner->getWindUnit());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://api.netatmo.com/api/getstationsdata?access_token=' . $accessToken,
            $accessToken
        );

        try {
            //$response = $provider->getHttpClient()->send($request);
            //echo $response->getBody();
        } catch (RequestException $e) {
            echo "<h1>ERROR!</h1>";
            echo $e->getResponse()->getBody();
        }

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}

```
