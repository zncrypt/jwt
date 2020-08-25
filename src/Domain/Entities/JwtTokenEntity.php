<?php

namespace PhpBundle\Jwt\Domain\Entities;

/**
 * Class JwtTokenEntity
 *
 * @package php7rails\extension\jwt\entities
 *
 * @property $header array
 * @property $payload array
 * @property $sig string
 */
class JwtTokenEntity
{

    public $header;
    public $payload;
    public $sig;

    public function fieldType()
    {
        return [
            'header' => JwtHeaderEntity::class,
        ];
    }
}
