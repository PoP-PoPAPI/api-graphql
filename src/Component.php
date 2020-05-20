<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI;

use PoP\API\Component as APIComponent;
use PoP\Root\Component\AbstractComponent;
use PoP\GraphQLAPI\Config\ServiceConfiguration;
use PoP\Root\Component\CanDisableComponentTrait;
use PoP\Root\Component\YAMLServicesTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait, CanDisableComponentTrait;
    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\APIMirrorQuery\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        return [
            'migrate-api-graphql',
        ];
    }

    /**
     * Initialize services
     */
    protected static function doInitialize()
    {
        if (self::isEnabled()) {
            parent::doInitialize();
            self::initYAMLServices(dirname(__DIR__));
            ServiceConfiguration::init();
        }
    }

    protected static function resolveEnabled()
    {
        return APIComponent::isEnabled() && !Environment::disableGraphQLAPI();
    }
}
