<?php

namespace ZnCrypt\Jwt\Domain\Enums;

use ZnCrypt\Base\Domain\Enums\EncryptAlgorithmEnum;
use ZnCrypt\Base\Domain\Enums\EncryptFunctionEnum;

class JwtAlgorithmEnum
{

    const HS256 = 'HS256';
    const HS512 = 'HS512';
    const HS384 = 'HS384';
    const RS256 = 'RS256';
    const RS384 = 'RS384';
    const RS512 = 'RS512';

    public static $supportedAlgorithms = [
        JwtAlgorithmEnum::HS256 => [EncryptFunctionEnum::HASH_HMAC, EncryptAlgorithmEnum::SHA256],
        JwtAlgorithmEnum::HS512 => [EncryptFunctionEnum::HASH_HMAC, EncryptAlgorithmEnum::SHA512],
        JwtAlgorithmEnum::HS384 => [EncryptFunctionEnum::HASH_HMAC, EncryptAlgorithmEnum::SHA384],
        JwtAlgorithmEnum::RS256 => [EncryptFunctionEnum::OPENSSL, EncryptAlgorithmEnum::SHA256],
        JwtAlgorithmEnum::RS384 => [EncryptFunctionEnum::OPENSSL, EncryptAlgorithmEnum::SHA384],
        JwtAlgorithmEnum::RS512 => [EncryptFunctionEnum::OPENSSL, EncryptAlgorithmEnum::SHA512],
    ];

    public static function getHashAlgorithm($alg)
    {
        list($function, $algorithm) = self::$supportedAlgorithms[$alg];
        return $algorithm;
    }

    public static function isSupported($algorithm)
    {
        return array_key_exists($algorithm, self::$supportedAlgorithms);
    }
}
