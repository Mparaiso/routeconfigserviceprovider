<?php

/**
 *
 * @author MParaiso
 *
 */

namespace Mparaiso\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Silex\Application;

/**
 * FR : aide à la configuration des routes
 * EN : help route configuration
 */
class RouteLoader {

    protected $app;

    function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * FR : ajoute de multiples configurations de routes aux routes actuelles
     * EN : add multiple route resources to the current routes
     */
    public function append(array $routes = array()) {
        foreach ($routes as $route) {
            $this->add($route["type"], $route["path"], $route["prefix"]);
        }
    }

    /**
     * FR : ajoute une configuration de route aux routes actuelles
     * EN : add a route resource to the application route collection
     */
    public function add($type, $path, $prefix = NULL) {
        if (!is_file($path))
            throw new \Exception(" \$path must be a file ,  $path given ");
        if (isset($this->app["mp.route_loader.cache_dir"])) {
            $cacheDir = $this->app["mp.route_loader.cache_dir"];
            //$this->app["logger"]->log("writing cached route collection file, $cacheDir");
            $className = "RouteCollection_" . md5($path);
            $collectionCache = new ConfigCache($file = $cacheDir . "/" . $className . ".php", $this->app["debug"]);
            if (!$collectionCache->isFresh()) {
                $collection = $this->loadRouteCollection($type, $path);
                $dumper = new \Symfony\Component\Routing\Matcher\Dumper\PHPMatcherDumper($collection);
                $collectionCache->write(
                        $dumper->dump(array("class" => $className))
                );
            }
        } else {
            $collection = $this->loadRouteCollection($type, $path);
        }
        $this->app["routes"] = $this->app->share(
                $this->app->extend("routes", function($routes)use($collection, $prefix) {
                            $collection->addPrefix($prefix);
                            $routes->addCollection($collection);
                            return $routes;
                        })
        );
    }

    /**
     * FR : charge une collection de routew
     * @param string $type file type
     * @param string $path file path
     */
    protected function loadRouteCollection($type/* type de fichier */, $path/* chemin du fichier */) {
        $loaderClass = $this->getLoaderClass($type, $path);
        $loader = new $loaderClass(new FileLocator(dirname($path)));
        return $loader->load($path);
    }

    /* FR : retourne la class du loader */

    protected function getLoaderClass($type, $path) {
        switch ($type) {
            case "yaml":
                return '\Symfony\Component\Routing\Loader\YamlFileLoader';
                break;
            case "xml":
                return '\Symfony\Component\Routing\Loader\XmlFileLoader';
                break;
        }
        throw new \Exception("loader for type $type not found");
    }

}

