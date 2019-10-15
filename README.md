# GraphQL API

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
-->

It adds compatibility with GraphQL to the [PoP API](https://github.com/getpop/api): Execute queries against a single endpoint (`/api/graphql/?query=...`), and the response mirrors the structure of the query.

While the query input is different than GraphQL (added through URL param `fields`), the output is exactly the same.

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

## Usage

Query the single endpoint `/api/graphql/` through URL parameter `fields`:

- [/api/graphql/?query=posts.id|title|author.id|name](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|author.id|name)

### Visualize the schema

To visualize all available fields, use query field `__schema`: 

- [/api/graphql/?query=__schema](https://nextapi.getpop.org/api/graphql/?query=__schema)

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
