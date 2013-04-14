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
 *		$app["mp.route_loader"]->append(array(
 *				array(
 *						"type"=>"yaml",
 *						"path"=>__DIR__."/Resources/routes/routes.yml",
 *						"prefix"=>"/",
 *					),
 *               ...
 *		));
 *
 */
class RouteConfigServiceProvider implements ServiceProviderInterface {
	function __construct($namespace="mp"){
		$this->ns = $namespace;
	}
    public function boot(Application $app) {
    }

    public function register(Application $app) {
		$app["$this->ns.route_collections"]=array();
		$app["$this->ns.route_loader"]= $app->share(function($app){
			$routeLoader =   new RouteLoader($app);
			$routeLoader->append($app["$this->ns.route_collections"]);
			return $routeLoader;
		});
    }

}



