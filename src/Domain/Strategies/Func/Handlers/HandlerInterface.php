<?php

namespace PhpBundle\Jwt\Domain\Strategies\Func\Handlers;

interface HandlerInterface
{

    public function sign($msg, $algorithm, $key);

    public function verify($msg, $algorithm, $key, $signature);
}
