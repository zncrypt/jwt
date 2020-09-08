<?php

namespace ZnCrypt\Jwt\Domain\Strategies\Func\Handlers;

use DomainException;

class OpenSslStrategy implements HandlerInterface
{

    public function sign($msg, $algorithm, $key)
    {
        $signature = '';
        $success = openssl_sign($msg, $signature, $key, $algorithm);
        if ( ! $success) {
            throw new DomainException("OpenSSL unable to sign data");
        } else {
            return $signature;
        }
    }

    public function verify($msg, $algorithm, $key, $signature)
    {
        $success = openssl_verify($msg, $signature, $key, $algorithm);
        if ($success === 1) {
            return true;
        } elseif ($success === 0) {
            return false;
        }
        // returns 1 on success, 0 on failure, -1 on error.
        throw new DomainException('OpenSSL error: ' . openssl_error_string());
    }

}
