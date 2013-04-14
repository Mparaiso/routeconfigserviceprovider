# Route config service provider

Symfony route config integration for  Silex framework

author : M.Paraiso , contact mparaiso@online.fr

contact: mparaiso@online.fr

status: Work in progress

License: GPL

@copyright M.PARAISO

### Features

+ support yml and xml routing files
+ support caching route definitions files

### Usage

```php
        $app->register(new RouteConfigServiceProvider,array(
            "mp.route_loader.cache_dir"=>__DIR__."/../temp/routing/", /* cache directory for yml,xml files */
            "mp.route_collections"=>array(
                array(
                    "type"=>"yaml",
                    "path"=>__DIR__.'/Resource/routing/routes.yml',
                    "prefix"=>"/"
                )
            )
        ));
```
### Changelog

v0.0.5 RouteCollectionLoaderProvider renamed RouteConfigServiceProvider



