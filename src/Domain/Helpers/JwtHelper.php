<?php

namespace PhpBundle\Jwt\Domain\Helpers;

use PhpLab\Core\Legacy\Yii\Helpers\ArrayHelper;
use php7rails\app\domain\helpers\EnvService;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Domain\Libs\Alias;
use PhpLab\Core\Helpers\StringHelper;
use PhpBundle\Jwt\Domain\Entities\JwtEntity;
use PhpBundle\Jwt\Domain\Entities\JwtHeaderEntity;
use PhpBundle\Jwt\Domain\Entities\JwtProfileEntity;
use PhpBundle\Jwt\Domain\Entities\JwtTokenEntity;
use PhpBundle\Jwt\Domain\Enums\JwtAlgorithmEnum;
use UnexpectedValueException;

class JwtHelper
{

    public static function forgeBySubject(array $subject, JwtProfileEntity $profileEntity, $keyId = null): JwtEntity
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = $subject;
        $jwtEntity->token = self::sign($jwtEntity, $profileEntity, $keyId);
        return $jwtEntity;
    }

    public static function sign(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity, $keyId = null): string
    {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $keyId = $keyId ?: StringHelper::genUuid();
        $token = self::signToken($jwtEntity, $profileEntity, $keyId);
        return $token;
    }

    public static function decode(string $token, JwtProfileEntity $profileEntity): JwtEntity
    {
        //$profileEntity = ConfigProfileHelper::load($profileName, JwtProfileEntity::class);
        $tokenDto = JwtEncodeHelper::decode($token);
        JwtEncodeHelper::verifyTokenDto($tokenDto, $profileEntity);
        $jwtEntity = new JwtEntity($tokenDto->payload);
        $jwtEntity->token = $token;
        return $jwtEntity;
    }

    public static function decodeRaw(string $jwt, JwtProfileEntity $profileEntity): JwtTokenEntity
    {
        $tokenDto = JwtEncodeHelper::decode($jwt);
        $jwtTokenEntity = new JwtTokenEntity;
        $jwtTokenEntity->header = (array) $tokenDto->header;
        $jwtTokenEntity->payload = $tokenDto->payload;
        $jwtTokenEntity->sig = $tokenDto->signature;
        return $jwtTokenEntity;
    }

    private static function tokenDecodeItem(string $data)
    {
        $jsonCode = SafeBase64Helper::decode($data);
        $object = JwtJsonHelper::decode($jsonCode);
        if (null === $object) {
            throw new UnexpectedValueException('Invalid encoding');
        }
        return (array) $object;
    }

    private static function validateHeader(JwtHeaderEntity $headerEntity, JwtProfileEntity $profileEntity)
    {
        $key = $profileEntity->key;
        if (empty($headerEntity->alg)) {
            throw new UnexpectedValueException('Empty algorithm');
        }
        if ( ! JwtAlgorithmEnum::isSupported($headerEntity->alg)) {
            throw new UnexpectedValueException('Algorithm not supported');
        }
        if ( ! in_array($headerEntity->alg, $profileEntity->allowed_algs)) {
            throw new UnexpectedValueException('Algorithm not allowed');
        }
        if (is_array($key) || $key instanceof \ArrayAccess) {
            if (isset($headerEntity->kid)) {
                if ( ! isset($key[$headerEntity->kid])) {
                    throw new UnexpectedValueException('"kid" invalid, unable to lookup correct key');
                }
                //$key = $key[$headerEntity->kid];
            } else {
                throw new UnexpectedValueException('"kid" empty, unable to lookup correct key');
            }
        }
    }

    private static function signToken(JwtEntity $jwtEntity, JwtProfileEntity $profileEntity, string $keyId = null): string
    {
        if ($profileEntity->audience) {
            $jwtEntity->audience = ArrayHelper::merge($jwtEntity->audience, $profileEntity->audience);
        }
        if ( ! $jwtEntity->expire_at && $profileEntity->life_time) {
            $jwtEntity->expire_at = time() + $profileEntity->life_time;
        }
        $data = self::entityToToken($jwtEntity);

        $jwtHeaderEntity = new JwtHeaderEntity;
        $jwtHeaderEntity->alg = $profileEntity->default_alg;
        $jwtHeaderEntity->kid = $keyId;

        return JwtEncodeHelper::encode($data, $profileEntity->key, $jwtHeaderEntity);
    }

    private static function entityToToken(JwtEntity $jwtEntity): array
    {
        $data = EntityHelper::toArray($jwtEntity);
        $data = array_filter($data, function ($value) {
            return $value !== null;
        });
        $data = self::encodeAliases($data);
        return $data;
    }

    private static function encodeAliases(array $data): array
    {
        $alias = new Alias;
        $alias->setAliases([
            'issuer_url' => 'iss',
            'subject_url' => 'sub',
            'audience' => 'aud',
            'expire_at' => 'exp',
            'begin_at' => 'nbf',
        ]);
        $data = $alias->encode($data);
        return $data;
    }

}
