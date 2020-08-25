<?php

namespace PhpBundle\Jwt\Domain\Entities;

use PhpBundle\Jwt\Domain\Enums\JwtAlgorithmEnum;

/**
 * Class JwtHeaderEntity
 * @package PhpBundle\Crypt\Domain\Entities
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
