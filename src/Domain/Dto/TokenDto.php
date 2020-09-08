<?php

namespace ZnCrypt\Jwt\Domain\Dto;

/**
 * Class TokenDto
 * @package ZnCrypt\Base\Domain\Dto
 *
 * @property $header_encoded
 * @property $payload_encoded
 * @property $signature_encoded
 * @property $header
 * @property $payload
 * @property $signature
 */
class TokenDto
{

    public $header_encoded = null;
    public $payload_encoded = null;
    public $signature_encoded = null;

    public $header = null;
    public $payload = null;
    public $signature = null;

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['header_encoded']);
        unset($fields['payload_encoded']);
        unset($fields['signature_encoded']);
        return $fields;
    }

}
