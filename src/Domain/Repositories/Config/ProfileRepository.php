<?php

namespace PhpBundle\Jwt\Domain\Repositories\Config;

use PhpBundle\Jwt\Domain\Interfaces\Repositories\ProfileRepositoryInterface;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Enums\Measure\TimeEnum;
use PhpBundle\Jwt\Domain\Entities\JwtProfileEntity;
use PhpBundle\Jwt\Domain\Entities\KeyEntity;
use PhpLab\Core\Libs\Env\DotEnvHelper;

class ProfileRepository implements ProfileRepositoryInterface
{

    public function oneByName(string $profileName)
    {
        $prifile = DotEnvHelper::get('jwt.profiles.' . $profileName);
        $keyEntity = new KeyEntity;
        EntityHelper::setAttributes($keyEntity, $prifile['key']);
        $profileEntity = new JwtProfileEntity;
        $profileEntity->name = $profileName;
        $profileEntity->key = $keyEntity;
        $profileEntity->life_time = $prifile['life_time'] ?? TimeEnum::SECOND_PER_YEAR;
        return $profileEntity;
    }

}