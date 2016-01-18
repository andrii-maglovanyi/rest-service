<?php

namespace RestService\app;

use Pimple\Container;

class Config
{
    /**
     * Initialize container from configs and services files
     *
     * @param  Container $container Pimple container
     *
     * @return void
     */
    public static function init(Container $container)
    {
        self::config($container);
        self::services($container);
    }

    /**
     * Define container parameters from a config file
     *
     * @param  Container $container Pimple container
     *
     * @return void
     */
    private static function config(Container $container)
    {
        $config = yaml_parse_file(__DIR__.'/../config/config.yml');
        $params = self::recursiveConfigs($config);
        foreach ($params as $index => $param) {
            $container[$index] = $param;
        }
    }

    /**
     * Define container services from a config file
     *
     * @param  Container $container Pimple container
     *
     * @return void
     */
    private static function services(Container $container)
    {
        $services = yaml_parse_file(__DIR__.'/../config/services.yml');

        foreach ($services as $index => $service) {
            self::getArguments($service);
            $container[$index] = function ($c) use ($service) {
                return new $service['class'](
                    ...array_map(array($c, "offsetGet"),
                    str_replace("%", "", $service['arguments']))
                );
            };
        }
    }

    /**
     * Get arguments to be imported into services
     *
     * @param  array $service Array of information about a service
     *
     * @return void
     */
    private static function getArguments(&$service)
    {
        $arguments = array();
        if (isset($service['arguments'])) {
            // Arguments should be given to the array
            $arguments = is_array($service['arguments']) ? $service['arguments'] : array($service['arguments']);
        }

        $service['arguments'] = $arguments;
    }

    /**
     * Build dot separated configuration that reflect array nesting
     *
     * @param  array $config  Raw list of configs
     * @param  string $name   Name of config key
     * @param  array  $result List of configs with prepared keys
     *
     * @return array          List of configs with prepared keys
     */
    private static function recursiveConfigs($config, $name = '', &$result = array())
    {
        // Return array if config is listed sequentialy
        if (!count(array_filter(array_keys($config), 'is_string'))) {
            $result[$name] = $config;

            return $result;
        }

        // If config is associative
        foreach ($config as $index => $param) {
            // Combine nested values via dot
            $combinedName = empty($name) ? $index : $name.'.'.$index;
            if (is_array($param)) {
                $result = self::recursiveConfigs($param, $combinedName, $result);
            } else {
                $result[$combinedName] = $param;
            }
        }

        return $result;
    }
}
