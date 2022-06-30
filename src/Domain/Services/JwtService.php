<?php

namespace ZnCrypt\Jwt\Domain\Services;

use ZnCrypt\Jwt\Domain\Entities\JwtEntity;
use ZnCrypt\Jwt\Domain\Helpers\JwtEncodeHelper;
use ZnCrypt\Jwt\Domain\Helpers\JwtHelper;
use ZnCrypt\Jwt\Domain\Helpers\JwtModelHelper;
use ZnCrypt\Jwt\Domain\Interfaces\Repositories\ProfileRepositoryInterface;
use ZnCrypt\Jwt\Domain\Interfaces\Services\JwtServiceInterface;
use ZnCrypt\Jwt\Domain\Libs\ProfileContainer;

class JwtService implements JwtServiceInterface
{

    private $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function sign(JwtEntity $jwtEntity, string $profileName): string
    {
        $profileEntity = $this->profileRepository->findOneByName($profileName);
        $token = JwtHelper::sign($jwtEntity, $profileEntity);
        return $token;
    }

    public function verify(string $token, string $profileName): JwtEntity
    {
        $profileEntity = $this->profileRepository->findOneByName($profileName);
        $jwtEntity = JwtHelper::decode($token, $profileEntity);
        return $jwtEntity;
    }

    public function decode(string $token)
    {
        $jwtEntity = JwtModelHelper::parseToken($token);
        return $jwtEntity;
    }

    public function setProfiles($profiles)
    {
        if (is_array($profiles)) {
            $this->profileContainer = new ProfileContainer($profiles);
        } else {
            $this->profileContainer = $profiles;
        }
    }
}
