<?php

namespace Helper;

use DI\Container;

if (! function_exists('getContainer')) {
    /**
     * Helper for getting service container.
     *
     * @return Container
     */
    function getContainer(): Container
    {
        $containerBuilder = new \DI\ContainerBuilder();
        $containerBuilder->addDefinitions(__DIR__.'/config_di.php');
        return $containerBuilder->build();
    }
}
