<?php

namespace ZnCrypt\Jwt\Domain\Repositories\Config;

use ZnCore\Code\Helpers\PropertyHelper;
use ZnCore\DotEnv\Domain\Libs\DotEnvMap;
use ZnCrypt\Jwt\Domain\Entities\JwtProfileEntity;
use ZnCrypt\Jwt\Domain\Entities\KeyEntity;
use ZnCrypt\Jwt\Domain\Interfaces\Repositories\ProfileRepositoryInterface;
use ZnLib\Components\Time\Enums\TimeEnum;

class ProfileRepository implements ProfileRepositoryInterface
{

    public function findOneByName(string $profileName)
    {
        $prifile = DotEnvMap::get('jwt.profiles.' . $profileName);
        $keyEntity = new KeyEntity;
        PropertyHelper::setAttributes($keyEntity, $prifile['key']);
        $profileEntity = new JwtProfileEntity;
        $profileEntity->name = $profileName;
        $profileEntity->key = $keyEntity;
        $profileEntity->life_time = $prifile['life_time'] ?? TimeEnum::SECOND_PER_YEAR;
        return $profileEntity;
    }

}