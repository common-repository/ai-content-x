<?php
if (!class_exists('Jodacame_WP_Util'))
{
    class Jodacame_WP_Util
    {
        private static $_instance = null;

        /** 
         * Constructor
         * @return void
         * @since 1.0.0
         */
        public static function instance()
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        /**
         * Wordpress Die function
         * @param string $message message
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/wp_die/
         */

        public function die($message = '')
        {
            wp_die($message);
        }

        /**
         * Wordpress Send Json function
         * @param array $data data
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/wp_send_json/
         */

        public function json($data)
        {
            wp_send_json($data);
        }





        /**  
         * Wordpress enqueue script function
         * @param string $handle handle
         * @param string $src src
         * @param array $deps deps
         * @param string $ver ver
         * @param bool $in_footer in_footer
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/
         */


        public function enqueue_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
        {
            wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
        }

        /**  
         * Wordpress enqueue style function
         * @param string $handle handle
         * @param string $src src
         * @param array $deps deps
         * @param string $ver ver
         * @param string $media media
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style/
         */

        public function enqueue_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
        {
            wp_enqueue_style($handle, $src, $deps, $ver, $media);
        }

        /**  
         * Wordpress Localize a script
         * @param string $handle handle
         * @param string $object_name object_name
         * @param array $l10n l10n
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/wp_localize_script/
         */

        public function localize_script($handle, $object_name, $l10n)
        {
            wp_localize_script($handle, $object_name, $l10n);
        }

        /**
         * Retrieves the URL to the admin area for the current site.
         * @param string $path (optional) Path relative to the admin area URL. Default empty.
         * @param string $scheme (optional) The scheme to use. Default 'admin', which obeys force_ssl_admin() and is_ssl(). 'http' or 'https' can be passed to force those schemes.
         * @return string Admin area URL link with optional path appended.
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/admin_url/
         */

        public function admin_url($path = '', $scheme = 'admin')
        {
            return admin_url($path, $scheme);
        }

        public function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false)
        {
            return load_plugin_textdomain($domain, $deprecated, $plugin_rel_path);
        }

        public function sanitize_text_field($str)
        {
            return sanitize_text_field($str);
        }
    }
}
