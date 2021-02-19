<?php

return [
    'singletons' => [
        'ZnCrypt\Jwt\Domain\Interfaces\Services\JwtServiceInterface' => 'ZnCrypt\Jwt\Domain\Services\JwtService',
        'ZnCrypt\Jwt\Domain\Interfaces\Repositories\ProfileRepositoryInterface' => 'ZnCrypt\Jwt\Domain\Repositories\Config\ProfileRepository',
    ],
];