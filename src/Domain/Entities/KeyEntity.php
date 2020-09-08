<?php

namespace ZnCrypt\Jwt\Domain\Entities;

/**
 * Class KeyEntity
 * @package ZnCrypt\Base\Domain\Entities
 *
 * @property string $type
 * @property string $private
 * @property string $public
 * @property string $secret
 */
class KeyEntity
{

    public $type = null;
    public $private;
    public $public;
    public $secret;

    /*public function getType() {
        if(!empty($this->type)) {
            return $this->type;
        }
        if(!empty($this->secret)) {
            return EncryptFunctionEnum::HASH_HMAC;
        }
        if(!empty($this->private) || !empty($this->public)) {
            return EncryptFunctionEnum::OPENSSL;
        }
    }*/
}
