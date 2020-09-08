<?php

namespace ZnCrypt\Jwt\Domain\Helpers;

use ZnCrypt\Base\Domain\Helpers\SafeBase64Helper;

class JwtSegmentHelper
{

    public static function encodeSegment($data)
    {
        return SafeBase64Helper::encode(JwtJsonHelper::encode($data));
    }

    public static function decodeSegment($data)
    {
        return JwtJsonHelper::decode(SafeBase64Helper::decode($data));
    }

}
