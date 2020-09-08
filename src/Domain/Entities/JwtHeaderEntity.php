<?php

namespace ZnCrypt\Jwt\Domain\Entities;

use ZnCrypt\Jwt\Domain\Enums\JwtAlgorithmEnum;

/**
 * Class JwtHeaderEntity
 * @package ZnCrypt\Base\Domain\Entities
 *
 * @property $typ string
 * @property $alg string
 * @property $kid string
 */
class JwtHeaderEntity
{

    public $typ = 'JWT';
    public $alg = JwtAlgorithmEnum::HS256;
    public $kid;

}
