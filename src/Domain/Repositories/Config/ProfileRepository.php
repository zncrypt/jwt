<?php

namespace ZnCrypt\Jwt\Domain\Repositories\Config;

use ZnCore\Base\Enums\Measure\TimeEnum;
use ZnCore\Base\Libs\DotEnv\DotEnvFacade;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCrypt\Jwt\Domain\Entities\JwtProfileEntity;
use ZnCrypt\Jwt\Domain\Entities\KeyEntity;
use ZnCrypt\Jwt\Domain\Interfaces\Repositories\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{

    public function oneByName(string $profileName)
    {
        $prifile = DotEnvFacade::get('jwt.profiles.' . $profileName);
        $keyEntity = new KeyEntity;
        EntityHelper::setAttributes($keyEntity, $prifile['key']);
        $profileEntity = new JwtProfileEntity;
        $profileEntity->name = $profileName;
        $profileEntity->key = $keyEntity;
        $profileEntity->life_time = $prifile['life_time'] ?? TimeEnum::SECOND_PER_YEAR;
        return $profileEntity;
    }

}