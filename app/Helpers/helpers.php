<?php

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routes, $output = true) {
        if (is_string($routes)) {
            $routes = [$routes];
        }
        
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return $output;
            }
        }
        
        return !$output;
    }
}