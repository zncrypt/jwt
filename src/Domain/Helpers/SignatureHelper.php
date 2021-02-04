<?php

namespace ZnCrypt\Jwt\Domain\Helpers;

use domain\union\v1\entities\MemberEntity;
use ZnCrypt\Base\Domain\Enums\EncryptAlgorithmEnum;
use ZnCrypt\Base\Domain\Enums\EncryptFunctionEnum;

class SignatureHelper
{

    public static function sign($msg, $key, $function = EncryptFunctionEnum::HASH_HMAC, $algorithm = EncryptAlgorithmEnum::SHA256)
    {
        switch ($function) {
            case EncryptFunctionEnum::HASH_HMAC:
                return hash_hmac($algorithm, $msg, $key, true);
            case EncryptFunctionEnum::OPENSSL:
                $signature = '';
                $success = openssl_sign($msg, $signature, $key, $algorithm);
                if ( ! $success) {
                    throw new \Exception("OpenSSL unable to sign data");
                } else {
                    return $signature;
                }
        }
    }

    public static function verify($msg, $key, $signature, $function = EncryptFunctionEnum::HASH_HMAC, $algorithm = EncryptAlgorithmEnum::SHA256)
    {
        switch ($function) {
            case 'openssl':
                $success = openssl_verify($msg, $signature, $key, $algorithm);
                if ($success === 1) {
                    return true;
                } elseif ($success === 0) {
                    return false;
                }
                // returns 1 on success, 0 on failure, -1 on error.
                throw new \Exception(
                    'OpenSSL error: ' . openssl_error_string()
                );
            case 'hash_hmac':
            default:
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

}
