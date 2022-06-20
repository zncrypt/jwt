<?php

namespace ZnCrypt\Jwt\Domain\Entities;

use ZnCrypt\Base\Domain\Enums\EncryptAlgorithmEnum;

/**
 * Class ConfigEntity
 * @package ZnCrypt\Base\Domain\Entities
 *
 * @property KeyEntity $key
 * @property string $algorithm
 */
class ProfileEntity
{

    public $key;
    public $algorithm = EncryptAlgorithmEnum::SHA256;


    /*public function fieldType()
    {
        return [
            'key' => KeyEntity::class,
        ];
    }*/
}
