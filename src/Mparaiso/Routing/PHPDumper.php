<?php

namespace Mparaiso\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * PHPDumper
 * EN : Dump a route collection into a parsable php string returning the routeCollection
 * FR : crée une chaine de code PHP retournant une collection de route.
 *
 * @author Mparaiso <mparaiso@online.fr>
 */
class PHPDumper {

    /**
     *
     * @var RouteCollection 
     */
    protected $routeCollection;

    function __construct(RouteCollection $routeCollection) {
        $this->routeCollection = $routeCollection;
    }

    function dump() {
        $stamp = new \DateTime;
        $time = $stamp->format("r");
        $result = <<<EOF
<?php
/**
 *
 * Route Dump
 * This is a generated file , do not edit 
 * <timestamp>$time</timestamp>              
 */
                
 use Symfony\Component\Routing\RouteCollection;
 use Symfony\Component\Routing\Route;
        
 \$routeCollection = new RouteCollection();
                
        
EOF;
        $fields = array("Defaults", "Host", "Methods", "Options", "Path", "Requirements", "Schemes");
        foreach ($this->routeCollection->all() as $name => $route) {
            $result .= <<<EOF
\n
/**
 *                    
 * route named $name
 *                    
 */                   
EOF;
            $route_name = "\$route$name";
            $result.= "\n{$route_name} = new Route(".var_export($route->getPath(),true).");";
            /* @var $route Route */
            foreach ($fields as $field) {
                if ($route->{"get$field"}() != null) {
                    $result.= "\n{$route_name}->set$field(" . var_export($route->{"get$field"}(), true) . ");";
                }
            }
            // echo $name;
            //print_r($route);
            $result.= "\n\$routeCollection->add('$name',{$route_name});";
        }

        $result.= "\nreturn \$routeCollection;";


        return $result;
    }

}
