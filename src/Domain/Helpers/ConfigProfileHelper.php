<?php

namespace ZnCrypt\Jwt\Domain\Helpers;

use ZnCore\Contract\Common\Exceptions\InvalidConfigException;
use ZnCore\Code\Helpers\DeprecateHelper;
use ZnCrypt\Base\Domain\Entities\ConfigEntity;
use ZnCrypt\Jwt\Domain\Entities\ProfileEntity;

class ConfigProfileHelper
{

    public static function load(string $profile, $profileEntityClass = ProfileEntity::class): ProfileEntity
    {
        DeprecateHelper::hardThrow();
        $config = EnvService::get('encrypt.profiles.' . $profile);
        $profileEntity = self::createInstanse($config, $profileEntityClass);
        return $profileEntity;
    }

    public static function prepareDefinition($config): array
    {
        if (isset($config['key']['private_file'])) {
            $config['key']['private'] = file_get_contents($config['key']['private_file']);
        }
        if (isset($config['key']['public_file'])) {
            $config['key']['public'] = file_get_contents($config['key']['public_file']);
        }
        return $config;
    }

    private static function createInstanse($config, $profileEntityClass = ProfileEntity::class): ProfileEntity
    {

        if (isset($config['key']['private_file'])) {
            $config['key']['private'] = file_get_contents($config['key']['private_file']);
        }
        if (isset($config['key']['public_file'])) {
            $config['key']['public'] = file_get_contents($config['key']['public_file']);
        }

        //$profileEntityClass = ArrayHelper::getValue($config, 'class', $profileEntityClass);
        //unset($config['class']);
        $profileEntity = new $profileEntityClass($config);
        //ClassHelper::createObject($config);
        //

        //if(!$profileEntity->key instanceof KeyEntity) {
        if ( ! $profileEntity->key) {
            //d(debug_backtrace());
            throw new InvalidConfigException('Empty encryption key in profile!');
        }
        return $profileEntity;
    }

}
