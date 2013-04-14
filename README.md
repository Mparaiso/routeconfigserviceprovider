# Route config service provider

Symfony route config integration for  Silex framework

author : M.Paraiso , contact mparaiso@online.fr

contact: mparaiso@online.fr

status: Work in progress

License: GPL

@copyright M.PARAISO

### Usage

```php
 $app->register( new RouteConfigServiceProvider);
 $app["mp.route_loader"]->append(array(
    array(
        "type"=>"yaml",
        "path"=>__DIR__."/routes.yml",
 	"prefix"=>"/",
    ),        
 ));
```
### Changelog

v0.0.5 RouteCollectionLoaderProvider renamed RouteConfigServiceProvider



