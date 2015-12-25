<?php

namespace Auburus\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class NetatmoResourceOwner implements ResourceOwnerInterface
{
    const UNIT_METRIC = 0;
    const UNIT_IMPERIAL = 1;

    const WINDUNIT_KPH = 0;
    const WINDUNIT_MPH = 1;
    const WINDUNIT_MS  = 2;
    const WINDUNIT_BEAUFORT = 3;
    const WINDUNIT_KNOT = 4;

    const PRESSUREUNIT_MBAR = 0;
    const PRESSUREUNIT_INHG = 1;
    const PRESSUREUNIT_MMHG = 2;

    const FEEL_LIKE_ALGO_HUMIDEX = 0;
    const FEEL_LIKE_ALGO_HEAT_INDEX = 1;

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
