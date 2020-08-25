<?php

namespace PhpBundle\Jwt\Domain\Strategies\Func;

use PhpBundle\Crypt\Domain\Enums\EncryptFunctionEnum;
use PhpBundle\Jwt\Domain\Strategies\Func\Handlers\HandlerInterface;
use PhpBundle\Jwt\Domain\Strategies\Func\Handlers\HmacStrategy;
use PhpBundle\Jwt\Domain\Strategies\Func\Handlers\OpenSslStrategy;
use PhpLab\Core\Patterns\Strategy\Base\BaseStrategyContextHandlers;

/**
 * @property-read HandlerInterface $strategyInstance
 */
class FuncContext extends BaseStrategyContextHandlers
{

    public function getStrategyDefinitions()
    {
        return [
            EncryptFunctionEnum::OPENSSL => OpenSslStrategy::class,
            EncryptFunctionEnum::HASH_HMAC => HmacStrategy::class,
        ];
    }

    public function sign($msg, $algorithm, $key)
    {
        return $this->getStrategyInstance()->sign($msg, $algorithm, $key);
    }

    public function verify($msg, $algorithm, $key, $signature)
    {
        return $this->getStrategyInstance()->verify($msg, $algorithm, $key, $signature);
    }

}