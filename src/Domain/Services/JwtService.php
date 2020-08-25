<?php

namespace PhpBundle\Jwt\Domain\Services;

use PhpBundle\Jwt\Domain\Entities\JwtEntity;
use PhpBundle\Jwt\Domain\Helpers\JwtEncodeHelper;
use PhpBundle\Jwt\Domain\Helpers\JwtHelper;
use PhpBundle\Jwt\Domain\Interfaces\Repositories\ProfileRepositoryInterface;
use PhpBundle\Jwt\Domain\Interfaces\Services\JwtServiceInterface;
use PhpBundle\Jwt\Domain\Libs\ProfileContainer;

class JwtService implements JwtServiceInterface
{

    private $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function sign(JwtEntity $jwtEntity, string $profileName): string
    {
        $profileEntity = $this->profileRepository->oneByName($profileName);
        $token = JwtHelper::sign($jwtEntity, $profileEntity);
        return $token;
    }

    public function verify(string $token, string $profileName): JwtEntity
    {
        $profileEntity = $this->profileRepository->oneByName($profileName);
        $jwtEntity = JwtHelper::decode($token, $profileEntity);
        return $jwtEntity;
    }

    public function decode(string $token)
    {
        $jwtEntity = JwtEncodeHelper::decode($token);
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
