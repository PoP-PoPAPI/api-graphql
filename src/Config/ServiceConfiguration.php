<?php
namespace PoP\GraphQLAPI\Config;

use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\Root\Component\PHPServiceConfigurationTrait;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure()
    {
        ContainerBuilderUtils::injectServicesIntoService(
            'data_structure_manager',
            'PoP\\GraphQLAPI\\DataStructureFormatters',
            'add'
        );
    }
}
