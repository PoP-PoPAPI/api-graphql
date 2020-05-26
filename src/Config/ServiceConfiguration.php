<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI\Config;

use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\Root\Component\PHPServiceConfigurationTrait;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        ContainerBuilderUtils::injectServicesIntoService(
            'data_structure_manager',
            'PoP\\GraphQLAPI\\DataStructureFormatters',
            'add'
        );
    }
}
