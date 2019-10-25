<?php
namespace PoP\GraphQLAPI;

use PoP\Root\Component\AbstractComponent;
use PoP\API\Component as APIComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    /**
     * Initialize services
     */
    public static function init()
    {
        if (self::isEnabled()) {
            parent::init();
        }
    }

    protected static function initEnabled()
    {
        self::$enabled = APIComponent::isEnabled() && !Environment::disableGraphQLAPI();
    }

    public static function isEnabled()
    {
        // This is needed for if asking if this component is enabled before it has been initialized
        if (is_null(self::$enabled)) {
            self::initEnabled();
        }
        return self::$enabled;
    }
}
