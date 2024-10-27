<?php
if (!class_exists('Jodacame_WP_Menu'))
{
    class Jodacame_WP_Menu
    {
        private static $_instance = null;
        private $id = null; // identifier

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
         * Add menu page to WordPress
         * @param string $title
         * @param string $capability
         * @param string $slug
         * @param string $callback function name (Callback)
         * @param string $icon default null
         * @param int $position default null
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/add_menu_page/
         */

        public function create($title, $capability, $slug, $callback, $icon = null, $position = null)
        {
            add_menu_page($title, $title, $capability, $slug, $callback, $icon, $position);
        }

        /**
         * Add submenu page to WordPress
         * @param string $title
         * @param string $capability
         * @param string $slug
         * @param string $callback function name (Callback)
         * @param string $icon default null
         * @param int $position default null
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/add_submenu_page/
         */
        public function add($title, $capability, $slug, $callback = null, $icon = null, $position = null)
        {
            add_submenu_page($this->id, $title, $title, $capability, $slug, $callback, $icon, $position);
        }
    }
}
