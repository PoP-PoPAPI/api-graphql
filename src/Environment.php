<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI;

class Environment
{
    public static function disableGraphQLAPI(): bool
    {
        return isset($_ENV['DISABLE_GRAPHQL_API']) ? strtolower($_ENV['DISABLE_GRAPHQL_API']) == "true" : false;
    }
}
