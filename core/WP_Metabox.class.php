<?php
if (!class_exists('Jodacame_WP_Metabox'))
{
    class Jodacame_WP_Metabox
    {
        private static $_instance = null;
        private $id = null; // User Identifier
        /** 
         * Constructor
         * @return void
         * @since 1.0.0
         */
        public static function instance($id = null)
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            self::$_instance->id = $id;
            return self::$_instance;
        }

        /**
         * Add metabox to WordPress
         * @param string $id
         * @param string $title
         * @param string $callback
         * @param string $screen
         * @param string $context default 'advanced'
         * @param string $priority default 'default'
         * @param string $callback_args default null
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/add_meta_box/
         */

        public  function create($id, $title, $callback, $screen, $context = 'advanced', $priority = 'default', $callback_args = null)
        {
            add_meta_box($id, $title, $callback, $screen, $context, $priority, $callback_args);
        }
    }
}
