<?php
class Jodacame_WP_Rest
{
    private static $_instance = null;
    public static function instance()
    {
        if (is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __construct()
    {
        // do nothing
    }
    public function register_route($namespace, $route, $args = array(), $override = false)
    {
        if ($override)
        {
            add_action('rest_api_init', function () use ($namespace, $route, $args)
            {
                register_rest_route($namespace, $route, $args);
            });
        }
        else
        {
            add_action('rest_api_init', function () use ($namespace, $route, $args)
            {
                register_rest_route($namespace, $route, $args);
            }, 11);
        }
    }
}
