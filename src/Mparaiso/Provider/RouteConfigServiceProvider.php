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
 * $app->register( new RouteCollectionLoaderProvider,array(
 *     'mp.route_loader.cache'=>__DIR__."/Temp/",
 *     "mp.route_loader.debug"=>false,
 *     "mp.route_collections"=>array(
 *         "type"=>"yaml",
 *         "path"=>"routes.yaml",
 *         "prefix"=>"/prefix",
 *     )
 * ));
 * $app["mp.route_loader"]->append(array(
 *                  array(
 *                  "type"=>"yaml",
 *                  "path"=>__DIR__."/Resources/routes/routes.yml",
 *                  "prefix"=>"/",
 *                  ),
 *               ...
 * ));
 *
 */
class RouteConfigServiceProvider implements ServiceProviderInterface {

    public function boot(Application $app) {
        $app["mp.route_loader"]->append($app["mp.route_collections"]);
    }

    public function register(Application $app) {
        $app["mp.route_collections"] = array();
        $app["mp.route_loader.parameters"] = array();
        $app['mp.route_loader.cache'] = null;
        $app["mp.route_loader.debug"] = function($app) {
                    return $app["debug"];
                };

        $app["mp.route_loader"] = $app->share(function($app) {
                    $loader = new RouteLoader($app['routes'], $app['mp.route_loader.cache'], $app["mp.route_loader.debug"]);
                    return $loader;
                }
        );
    }

}

