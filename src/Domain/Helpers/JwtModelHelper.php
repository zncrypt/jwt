<?php

namespace ZnCrypt\Jwt\Domain\Helpers;

use ArrayAccess;
use DateTime;
use DomainException;
use ZnCrypt\Base\Domain\Helpers\SafeBase64Helper;
use ZnCrypt\Jwt\Domain\Dto\TokenDto;
use ZnCrypt\Jwt\Domain\Enums\JwtAlgorithmEnum;
use ZnCrypt\Jwt\Domain\Exceptions\BeforeValidException;
use ZnCrypt\Jwt\Domain\Exceptions\ExpiredException;
use UnexpectedValueException;

class JwtModelHelper
{

    /**
     * When checking begin_at, iat or expiration times,
     * we want to provide some extra leeway time to
     * account for clock skew.
     */
    public static $timeOffset = 0;

    public static function parseToken(string $jwt): TokenDto
    {
        $tokenDto = self::tokenToDto($jwt);
        self::decodeTokenDto($tokenDto);
        self::validateTokenDto($tokenDto);
        return $tokenDto;
    }

    private static function tokenToDto(string $jwt): TokenDto
    {
        $tokenSegments = explode('.', $jwt);
        if (count($tokenSegments) != 3) {
            throw new UnexpectedValueException('Wrong number of segments');
        }

        $tokenDto = new TokenDto;
        list($tokenDto->header_encoded, $tokenDto->payload_encoded, $tokenDto->signature_encoded) = $tokenSegments;
        return $tokenDto;
    }

    private static function decodeTokenDto(TokenDto $tokenDto)
    {
        $tokenDto->header = JwtSegmentHelper::decodeSegment($tokenDto->header_encoded);
        $tokenDto->payload = JwtSegmentHelper::decodeSegment($tokenDto->payload_encoded);
        $tokenDto->signature = SafeBase64Helper::decode($tokenDto->signature_encoded);
    }

    private static function validateTokenDto(TokenDto $tokenDto)
    {
        if (null === $tokenDto->header_encoded) {
            throw new UnexpectedValueException('Invalid header');
        }
        if (null === $tokenDto->payload_encoded) {
            throw new UnexpectedValueException('Invalid claims');
        }
        if (false === $tokenDto->signature_encoded) {
            throw new UnexpectedValueException('Invalid signature');
        }
        if (null === $tokenDto->header) {
            throw new UnexpectedValueException('Invalid header encoding');
        }
        if (null === $tokenDto->payload) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }
        if (false === $tokenDto->signature) {
            throw new UnexpectedValueException('Invalid signature encoding');
        }
    }

    public static function verifyTime(TokenDto $tokenDto)
    {
        $timestamp = time();

        // Check if the nbf if it is defined. This is the time that the
        // token can actually be used. If it's not yet that time, abort.
        if (isset($tokenDto->payload->nbf) && $tokenDto->payload->nbf > ($timestamp + static::$timeOffset)) {
            throw new BeforeValidException(
                'Cannot handle token prior to ' . date(DateTime::ISO8601, $tokenDto->payload->nbf)
            );
        }

        // Check that this token has been created before 'now'. This prevents
        // using tokens that have been created for later use (and haven't
        // correctly used the nbf claim).
        if (isset($tokenDto->payload->iat) && $tokenDto->payload->iat > ($timestamp + static::$timeOffset)) {
            throw new BeforeValidException(
                'Cannot handle token prior to ' . date(DateTime::ISO8601, $tokenDto->payload->iat)
            );
        }

        // Check if this token has expired.
        if (isset($tokenDto->payload->exp) && ($timestamp - static::$timeOffset) >= $tokenDto->payload->exp) {
            throw new ExpiredException('Expired token');
        }
    }

    public static function validateToken(TokenDto $tokenDto, array $allowed_algs)
    {
        $alg = $tokenDto->header->alg;
        if (empty($alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if ( ! JwtAlgorithmEnum::isSupported($alg)) {
            throw new DomainException('Algorithm not supported');
        }
        if ( ! JwtAlgorithmEnum::isSupported($alg)) {
            throw new UnexpectedValueException('Algorithm not supported');
        }
        if ( ! in_array($alg, $allowed_algs)) {
            throw new UnexpectedValueException('Algorithm not allowed');
        }
    }

    public static function validateKey(TokenDto $tokenDtostring, $key)
    {
        if (is_array($key) || $key instanceof ArrayAccess) {
            if (isset($tokenDto->header->kid)) {
                if ( ! isset($key[$tokenDto->header->kid])) {
                    throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
                }
                //$key = $key[$tokenDto->header->kid];
            } else {
                throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
            }
        }
    }

}
