<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class NetatmoResourceOwner implements ResourceOwnerInterface
{
    protected $user;

    public function __construct(array $user_response)
    {
        $this->user = $user_response;
    }

    public function getId()
    {
        return $this->getMail();
    }

    public function getMail()
    {
        return $this->user['mail'];
    }

    public function getCountry()
    {
        return $this->user['administrative']['country'];
    }

    public function getRegLocale()
    {
        return $this->user['administrative']['reg_locale'];
    }

    public function getLang()
    {
        return $this->user['administrative']['lang'];
    }

    public function getUnit()
    {
        return $this->user['administrative']['unit'];
    }

    public function getWindUnit()
    {
        return $this->user['administrative']['windunit'];
    }

    public function getPressureUnit()
    {
        return $this->user['administrative']['pressureunit'];
    }

    public function getFeelLikeAlgo()
    {
        return $this->user['administrative']['feel_like_algo'];
    }

    public function toArray()
    {
        return $this->user;
    }
}
