<?php

/**
 *
 * @author MParaiso
 *
 */

namespace Mparaiso\Routing;

use Silex\Application;
use Silex\RequestContext;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\Dumper\PHPMatcherDumper;

/**
 * FR : aide à la configuration des routes
 * EN : help route configuration
 */
class RouteLoader {

    protected $routes;

    function __construct(RouteCollection $routes, $cacheDir = null, $debug = false) {
        $this->routes = $routes;
        $this->cacheDir = $cacheDir;
        $this->debug = $debug;
    }

    /**
     * FR : ajoute de multiples configurations de routes aux routes actuelles
     * EN : add multiple route resources to the current routes
     */
    public function append(array $route_resources = array()) {
        foreach ($route_resources as $route) {
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
        #@TODO fix that stuff  : problem : some kind of PHPRouteCollectionDumper needs to be created
        #see below
        if ($this->cacheDir != null) {
            $className = "RouteCollection_" . md5($path);
            $collectionCache = new ConfigCache($file = $this->cacheDir . "/" . $className . ".php", $this->debug);
            if (!$collectionCache->isFresh()) {
                $collection = $this->loadRouteCollection($type, $path);
                $dumper = new \Mparaiso\Routing\PHPDumper($collection);
                $collectionCache->write($dumper->dump(),$collection->getResources());
            } else {
                $collection = require_once $file;
            }
        } else {

            $collection = $this->loadRouteCollection($type, $path);
        }
        $collection->addPrefix($prefix);
        $this->routes->addCollection($collection);
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
