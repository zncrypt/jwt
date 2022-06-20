<?php

namespace ZnCrypt\Jwt\Domain\Entities;

/**
 * @property $header array
 * @property $payload array
 * @property $sig string
 */
class JwtTokenEntity
{

    public $header;
    public $payload;
    public $sig;

    /*public function fieldType()
    {
        return [
            'header' => JwtHeaderEntity::class,
        ];
    }*/
}
