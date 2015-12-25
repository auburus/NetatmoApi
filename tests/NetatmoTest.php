<?php

namespace Auburus\OAuth2\Client\Test;

use Auburus\OAuth2\Client\Provider\Netatmo;
use Auburus\OAuth2\Client\Provider\NetatmoStation;
use Auburus\OAuth2\Client\Provider\NetatmoThermostat;
use Auburus\OAuth2\Client\Provider\NetatmoHome;
use Auburus\OAuth2\Client\Provider\NetatmoResourceOwner;

use Mockery as m;

class NetatmoTest extends \PHPUnit_Framework_TestCase
{
    protected $config = [
        'clientId' => 'mock_client_id',
        'clientSecret' => 'mock_client_secret',
        'redirectUri' => 'redirect_uri'
    ];

    public function testBaseAuthorizationUrl()
    {
        $provider = new Netatmo($this->config);

        $this->assertEquals(
            'https://api.netatmo.net/oauth2/authorize',
            $provider->getBaseAuthorizationUrl()
        );
    }

    public function testBaseAccessTokenUrl()
    {
        $provider = new Netatmo($this->config);

        $this->assertEquals(
            'https://api.netatmo.net/oauth2/token',
            $provider->getBaseAccessTokenUrl([])
        );
    }

    public function testScopeSeparator()
    {
        $provider = new Netatmo($this->config);

        $this->assertEquals(' ', $provider->getScopeSeparator());
    }

    public function testGetResourceOwner()
    {
        $provider = new Netatmo($this->config);

        $response = [
            'body' => [
                'user' => [
                    'mail' => 'toto@netatmo.com',
                    'administrative' => [
                        'reg_locale' => 'en-US',
                        'lang' => 'en-US',
                        'unit' => 0,
                        'windunit' => 3,
                        'pressureunit' => 2,
                        'feel_like_algo' => 1,
                    ],
                ],
            ],
        ];

        $user = $provider->createResourceOwner($response, $this->getMockToken());

        $this->assertInstanceOf(
            '\League\OAuth2\Client\Provider\ResourceOwnerInterface',
            $user
        );

        $this->assertInstanceOf(
            '\Auburus\OAuth2\Client\Provider\NetatmoResourceOwner',
            $user
        );

        $this->assertEquals('toto@netatmo.com', $user->getId());
        $this->assertEquals('toto@netatmo.com', $user->getMail());
        $this->assertEquals('en-US', $user->getRegLocale());
        $this->assertEquals('en-US', $user->getLang());
        $this->assertSame(0, $user->getUnit(), 'Unit must coincide');
        $this->assertSame(3, $user->getWindUnit(), 'Wind unit must coincide');
        $this->assertSame(2, $user->getPressureUnit(), 'Pressure unit must coincide');
        $this->assertSame(1, $user->getFeelLikeAlgo(), 'Feel like algo must coincide');

        $this->assertEquals($response['body']['user'], $user->toArray());
    }

    public function testNetatmo()
    {
        $provider = new Netatmo($this->config);

        $this->assertEquals([], $provider->getDefaultScopes());

        $this->setExpectedException(
            'Auburus\OAuth2\Client\Provider\Exception\ResourceOwnerException'
        );
        $provider->getResourceOwnerDetailsUrl($this->getMockToken());
    }

    public function testNetatmoHome()
    {
        $provider = new NetatmoHome($this->config);

        $this->assertEquals(['read_camera'], $provider->getDefaultScopes());

        $token = $this->getMockToken()
            ->shouldReceive('__toString')
            ->once()
            ->andReturn('12345')
            ->getMock();
        $this->assertEquals(
            'https://api.netatmo.net/api/gethomedata?access_token=12345',
            $provider->getResourceOwnerDetailsUrl($token)
        );
    }

    public function testNetatmoStation()
    {
        $provider = new NetatmoStation($this->config);

        $this->assertEquals(['read_station'], $provider->getDefaultScopes());

        $token = $this->getMockToken()
            ->shouldReceive('__toString')
            ->once()
            ->andReturn('12345')
            ->getMock();
        $this->assertEquals(
            'https://api.netatmo.net/api/getstationsdata?access_token=12345',
            $provider->getResourceOwnerDetailsUrl($token)
        );
    }

    public function testNetatmoThermostat()
    {
        $provider = new NetatmoThermostat($this->config);

        $this->assertEquals(['read_thermostat'], $provider->getDefaultScopes());

        $token = $this->getMockToken()
            ->shouldReceive('__toString')
            ->once()
            ->andReturn('12345')
            ->getMock();
        $this->assertEquals(
            'https://api.netatmo.net/api/getthermostatsdata?access_token=12345',
            $provider->getResourceOwnerDetailsUrl($token)
        );
    }

    protected function getMockToken()
    {
        $token = m::mock('League\OAuth2\Client\Token\AccessToken');
        return $token;

    }

    public function tearDown()
    {
        m::close();
    }
}
