<?php
if (!class_exists('Jodacame_WP_Hook'))
{
    class Jodacame_WP_Hook
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

        /**
         * Add action hook to WordPress
         * @param string $hook_name
         * @param string $function_to_add function name (Callback)
         * @param int $priority  default 10
         * @param int $accepted_args default 1
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/add_action/
         */
        public function add_action($hook_name, $function_to_add, $priority = 10, $accepted_args = 1)
        {
            add_action($hook_name, $function_to_add, $priority, $accepted_args);
        }

        /**
         * Add filter hook to WordPress
         * @param string $hook_name
         * @param string $function_to_add function name (Callback)
         * @param int $priority  default 10
         * @param int $accepted_args default 1
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/add_filter/
         */

        public function add_filter($hook_name, $function_to_add, $priority = 10, $accepted_args = 1)
        {
            add_filter($hook_name,  $function_to_add, $priority, $accepted_args);
        }

        /**
         * Call action hook to WordPress
         * @param string $hook_name
         * @param mixed $arg default null
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/do_action/
         */

        public function do_action($hook_name, $arg = array())
        {
            do_action($hook_name, $arg);
        }

        /**
         * Set the activation hook for a plugin.
         * @param string $file
         * @param string $function
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/register_activation_hook/
         */

        public function register_activation($file, $function)
        {
            register_activation_hook($file, $function);
        }

        /**
         * Set the deactivation hook for a plugin.
         * @param string $file
         * @param string $function
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/register_deactivation_hook/
         */

        public function register_deactivation($file, $function)
        {
            register_deactivation_hook($file, $function);
        }
    }
}
