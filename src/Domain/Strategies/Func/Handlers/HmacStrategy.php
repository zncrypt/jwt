<?php

namespace ZnCrypt\Jwt\Domain\Strategies\Func\Handlers;

use ZnCrypt\Jwt\Domain\Helpers\EncryptHelper;

class HmacStrategy implements HandlerInterface
{

    public function sign($msg, $algorithm, $key)
    {
        return hash_hmac($algorithm, $msg, $key, true);
    }

    public function verify($msg, $algorithm, $key, $signature)
    {
        $hash = hash_hmac($algorithm, $msg, $key, true);
        if (function_exists('hash_equals')) {
            return hash_equals($signature, $hash);
        }
        $len = min(EncryptHelper::safeStrlen($signature), EncryptHelper::safeStrlen($hash));

        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= (ord($signature[$i]) ^ ord($hash[$i]));
        }
        $status |= (EncryptHelper::safeStrlen($signature) ^ EncryptHelper::safeStrlen($hash));

        return ($status === 0);
    }

}
