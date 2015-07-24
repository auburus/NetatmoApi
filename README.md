# NetatmoApi

This is a Provider implementation for the [league/OAuth2-client](https://github.com/thephpleague/oauth2-client) library.

## Install

Via Composer

``` bash
$ composer require league/oauth2-client
```

## Usage

This is an (slightly changed) copy of the provided 
[usage](https://github.com/thephpleague/oauth2-client/blob/master/README.md#usage) example.

``` php
$provider = new Auburus\OAuth2\Client\Provider\Netatmo([
    'clientId'      => 'XXXXXXXX',
    'clientSecret'  => 'XXXXXXXX',
    'redirectUri'   => 'https://your-registered-redirect-uri/',
    'scopes'        => ['email', '...', '...'],
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->state();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $userDetails = $provider->getUserDetails($token);

        // Use these details to create a new profile
        printf('Hello %s!', $userDetails->firstName);

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->accessToken;

    // Use this to get a new access token if the old one expires
    echo $token->refreshToken;

    // Unix timestamp of when the token will expire, and need refreshing
    echo $token->expires;
```
