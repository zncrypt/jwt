<?php

namespace PhpBundle\Jwt\Domain\Enums;

use PhpLab\Core\Domain\Base\BaseEnum;

class EncryptKeyTypeEnum extends BaseEnum
{

    const PRIVATE = 'private';
    const PUBLIC = 'public';

}
