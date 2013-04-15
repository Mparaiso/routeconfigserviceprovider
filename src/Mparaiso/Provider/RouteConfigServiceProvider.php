<?php

/**
 *
 * @author MParaiso
 *
 */

namespace Mparaiso\Provider;

use Symfony\Component\Config\FileLocator;
use Mparaiso\Routing\RouteLoader;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Silex\ServiceProviderInterface;
use Silex\Application;

/**
 *
 * Usage :
 * $app->register( new RouteCollectionLoaderProvider);
 * 		$app["mp.route_loader"]->append(array(
 *                  array(
 *                  "type"=>"yaml",
 *                  "path"=>__DIR__."/Resources/routes/routes.yml",
 *                  "prefix"=>"/",
 *                  ),
 *               ...
 * 		));
 *
 */
class RouteConfigServiceProvider implements ServiceProviderInterface {


    function __construct() {
    }

    public function boot(Application $app) {
        $app["mp.route_loader"]->append($app["mp.route_collections"]);
    }

    public function register(Application $app) {
        $app["mp.route_collections"] = array();
        $app["mp.route_loader.parameters"] = array();
        $app["mp.route_loader"] = $app->share(function($app) {
                    return new RouteLoader($app['routes']);
                }
        );
    }

}

