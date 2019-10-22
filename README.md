# GraphQL API

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
-->

“Schemaless” implementation of GraphQL, using components. It makes a PoP application become a GraphQL server.

## Install

Via Composer

``` bash
$ composer require getpop/api-graphql dev-master
```

**Note:** Your `composer.json` file must have the configuration below to accept minimum stability `"dev"` (there are no releases for PoP yet, and the code is installed directly from the `master` branch):

```javascript
{
    ...
    "minimum-stability": "dev",
    "prefer-stable": true,
    ...
}
```

### Enable pretty permalinks

Add the following code in the `.htaccess` file to enable API endpoint `/api/graphql/`:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# Rewrite from /some-url/api/graphql/ to /some-url/?scheme=api&datastructure=graphql
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^(.*)/api/graphql/?$ /$1/?scheme=api&datastructure=graphql [L,P,QSA]

# b. Homepage single endpoint (root)
# Rewrite from api/graphql/ to /?scheme=api&datastructure=graphql
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^api/graphql/?$ /?scheme=api&datastructure=graphql [L,P,QSA]
</IfModule>
```
<!--
## Usage

### Syntax

The query syntax used is described in package [Field Query](https://github.com/getpop/field-query).
-->
## Implementation based on components, not on schemas

Whereas a standard implementation of [GraphQL](https://graphql.org) is based on the concept of [schemas and types](https://graphql.org/learn/schema/), GraphQL API for PoP is, instead, [implemented using components](https://www.smashingmagazine.com/2019/01/introducing-component-based-api/)

This architectural decision has several advantages over a schema-based implementation, explained in the sections below.

## Automatically-generated schema

This implementation is called “schemaless” only because the developer does not need to create schemas to represent the data model. Instead, the schema is **automatically-generated from the component model itself**, simply by coding classes following OOP principles. 

As a consequence, there is no need to manually define the hundreds (or even thousands) of properties on the schema, which leads to increased productivity.

Similar to GraphQL, the schema can be inspected through the introspection `"__schema"` field:

- [/api/graphql/?query=__schema](https://nextapi.getpop.org/api/graphql/?query=__schema)

## Syntax supporting URL-based queries

GraphQL API for PoP supports a [different syntax](https://github.com/getpop/field-query) than the one defined in the [GraphQL spec](https://graphql.github.io/graphql-spec/), which in addition to supporting all the expected features (arguments, variables, directives, etc), also grants GraphQL the following superpowers:

- Server-side caching
- Operators and Helper fields
- Nested fields

Please refer to the [Field Query](https://github.com/getpop/field-query) documentation to see examples on these superpowers.

## Fast speed, robust security

Resolving the query is fast: Wheareas the <a href="https://blog.acolyer.org/2018/05/21/semantics-and-complexity-of-graphql/">typical GraphQL implementation</a> has [complexity time](https://rob-bell.net/2009/06/a-beginners-guide-to-big-o-notation/) of `O(2^n)` in worst case, and `O(n^c)` to find out the query complexity, GraphQL API for PoP has complexity of `O(n^2)` in worst case, and `O(n)` in average case (where `n` is the number of nodes, both branches and leaves). 

As a consequence of this increased speed to resolve the query, DoS (Denial of Service) attacks are less effective, allowing to avoid having to spend time and energy in analyzing the query complexity.

## Natively decentralized/federated

The component-based architecture natively allows the data model to be split and worked upon by different, disconnected teams, without the need to set-up special tooling.

Additionally, field resolvers can be created on a field-by-field basis, based on the needs from the team/project/client, not on the API schema definition. This feature enables rapid iteration: Test new features, provide quick fixes, deprecate fields, and others.

For instance, let's say we want to add a field argument `length` on the `excerpt` field, but release it for testing first, before deciding to keep it or not. Then, we create a new field resolver that is enabled only when a property `branch` has the value `"experimental"`:

_**Standard behaviour:**_<br/>
[/?query=posts.id|title|excerpt](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt)

_**New feature not yet available:**_<br/>
<a href="https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt(length:30)">/?query=posts.id|title|excerpt(length:30)</a>

_**New feature available under "experimental" branch:**_<br/>
<a href="https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt(branch:experimental,length:30)">[/?query=posts.id|title|excerpt(length:30,branch:experimental)</a>

## Query data on resources, the REST way

In addition to querying data from the single endpoint `/api/graphql/` (which represents the root), it is possible to query data on specific resources, as defined by their URL.

Or, in other words, you can use a GraphQL query to retrieve data from a REST endpoint.

For instance, we can query data for a [single post](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/) or a [collection of posts](https://nextapi.getpop.org/posts/) by appending `/api/graphql/` to the URL, and adding the `query` URL parameter:

- [{single-post-url}/?query=id|title|author.id|name](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/graphql/?query=id|title|author.id|name)
- [{post-list-url}/?query=id|title|author.id|name](https://nextapi.getpop.org/posts/api/graphql/?query=id|title|author.id|name)

## Query examples

Please refer to the [Field Query](https://github.com/getpop/field-query) documentation to see plenty of query examples.

## More information

Please refer to package [API](https://github.com/getpop/api), on which the GraphQL API is based, and which contains plenty of extra documentation.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/getpop/api-graphql.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/api-graphql/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/api-graphql.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/api-graphql.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/api-graphql.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/api-graphql
[link-travis]: https://travis-ci.org/getpop/api-graphql
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/api-graphql/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/api-graphql
[link-downloads]: https://packagist.org/packages/getpop/api-graphql
[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors
