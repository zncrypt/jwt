<?php

namespace PhpBundle\Jwt\Domain\Entities;

use PhpBundle\Crypt\Domain\Enums\EncryptAlgorithmEnum;

/**
 * Class ConfigEntity
 * @package PhpBundle\Crypt\Domain\Entities
 *
 * @property KeyEntity $key
 * @property string $algorithm
 */
class ProfileEntity
{

    public $key;
    public $algorithm = EncryptAlgorithmEnum::SHA256;


    public function fieldType()
    {
        return [
            'key' => KeyEntity::class,
        ];
    }
}
