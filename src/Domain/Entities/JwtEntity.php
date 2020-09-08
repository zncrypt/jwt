<?php

namespace ZnCrypt\Jwt\Domain\Entities;

/**
 * @property $token string
 * @property $issuer_url string iss: чувствительная к регистру строка или URI, которая является уникальным идентификатором стороны, генерирующим токен
 * @property $subject array|\Object
 * @property $subject_url string sub: чувствительная к регистру строка или URI, которая является уникальным идентификатором стороны, о которой содержится информация в данном токене. Значения с этим ключом должны быть уникальны в контексте стороны, генерирующей JWT.
 * @property $audience string[] aud: массив чувствительных к регистру строк или URI, являющийся списком получателей данного токена. Когда принимающая сторона получает JWT с данным ключом, она должна проверить наличие себя в получателях — иначе проигнорировать токен
 * @property $expire_at integer exp: время в формате Unix Time, определяющее момент, когда токен станет не валидным
 * @property $begin_at integer nbf: в противоположность ключу exp, это время в формате Unix Time, определяющее момент, когда токен станет валидным
 */
class JwtEntity
{

    public $token;
    public $issuer_url;
    public $subject;
    public $subject_url;
    public $audience = [];
    public $expire_at;
    public $begin_at;

}
