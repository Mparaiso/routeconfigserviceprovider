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
   Usage :
   $app->register( new RouteCollectionLoaderProvider,array(
       'mp.route_loader.cache'=>__DIR__."/Temp/",
       "mp.route_loader.debug"=>false,
       "mp.route_collections"=>array(
           "type"=>"yaml",
           "path"=>"routes.yaml",
           "prefix"=>"/prefix",
       )
   ));
   $app["mp.route_loader"]->append(array(
                    array(
                    "type"=>"yaml",
                    "path"=>__DIR__."/Resources/routes/routes.yml",
                    "prefix"=>"/",
                    ),
                 ...
   ));
```
### Changelog
v0.0.21 Route Cache is back and fully working !
v0.0.8 cache removed because of problems, a proper dumper needs to be created
v0.0.5 RouteCollectionLoaderProvider renamed RouteConfigServiceProvider




