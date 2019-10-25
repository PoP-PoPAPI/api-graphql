<?php
namespace PoP\GraphQLAPI;

class Environment
{
    public static function disableGraphQLAPI()
    {
        return isset($_ENV['DISABLE_GRAPHQL_API']) ? strtolower($_ENV['DISABLE_GRAPHQL_API']) == "true" : false;
    }
}

