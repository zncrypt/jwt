<?php

namespace ZnCrypt\Jwt\Domain\Libs;

use php7extension\psr\container\BaseContainer;
//use ZnCore\Base\Legacy\Traits\ClassAttribute\MagicSetTrait;
use ZnCrypt\Jwt\Domain\Entities\JwtProfileEntity;
use ZnCrypt\Jwt\Domain\Helpers\ConfigProfileHelper;

class ProfileContainer extends BaseContainer
{

    //use MagicSetTrait;

    public function setProfiles($profiles)
    {

        $this->setDefinitions($profiles);
    }

    protected function prepareDefinition($component)
    {
        $component = parent::prepareDefinition($component);
        $component['class'] = JwtProfileEntity::class;
        $component = ConfigProfileHelper::prepareDefinition($component);
        return $component;
    }

}
