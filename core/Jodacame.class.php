<?php
if (!class_exists('Jodacame'))
{
    require_once  dirname(__FILE__) . '/Core_UI.class.php';
    require_once  dirname(__FILE__) . '/WP_Option.class.php';
    require_once  dirname(__FILE__) . '/WP_Setting.class.php';
    require_once  dirname(__FILE__) . '/WP_User.class.php';
    require_once  dirname(__FILE__) . '/WP_Post.class.php';
    require_once  dirname(__FILE__) . '/WP_Db.class.php';
    require_once  dirname(__FILE__) . '/WP_Scheduled.class.php';
    require_once  dirname(__FILE__) . '/WP_Hook.class.php';
    require_once  dirname(__FILE__) . '/WP_Util.class.php';
    require_once  dirname(__FILE__) . '/WP_Menu.class.php';
    require_once  dirname(__FILE__) . '/WP_Metabox.class.php';
    require_once  dirname(__FILE__) . '/WP_Nonce.class.php';
    require_once  dirname(__FILE__) . '/WP_Request.class.php';
    require_once  dirname(__FILE__) . '/WP_Rest.class.php';


    /**
     * Wordpress abstraction layer
     * @author Jodacame
     * @version 1.0.0
     * @since 1.0.0
     * @license MIT
     */

    class Jodacame
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

        /** 
         * Abstraction layer for UI
         * @return mixed Class Core_UI instance
         * @since 1.0.0
         */
        public function ui()
        {
            return Core_UI::instance();
        }

        /** 
         * Abstraction layer for get_option
         * @param string $option option name
         * @return mixed Class WP_Option instance
         * @since 1.0.0
         */

        public function wp_option($option = '')
        {
            return Jodacame_WP_Options::instance($option);
        }

        /** 
         * Abstraction layer for settings
         * @param string $id identifier
         * @return mixed Class WP_Setting instance
         * @since 1.0.0
         */

        public function wp_setting($id = '')
        {
            return Jodacame_WP_Settings::instance($id);
        }

        /** 
         * Abstraction layer for user
         * @param string $id identifier or 'me' for current user
         * @return mixed Class WP_User instance
         * @since 1.0.0
         */
        public function wp_user($id = 'me')
        {
            return Jodacame_WP_User::instance($id);
        }

        /** 
         * Abstraction layer for post
         * @param string $id identifier
         * @return mixed Class WP_Post instance
         * @since 1.0.0
         */
        public function wp_post($post_id = null)
        {
            return Jodacame_WP_Post::instance($post_id);
        }

        public function wp_db()
        {
            return Jodacame_WP_Db::instance();
        }

        public function wp_scheduled($hook = null)
        {
            return Jodacame_WP_Scheduled::instance($hook);
        }

        public function wp_hook()
        {
            return Jodacame_WP_Hook::instance();
        }
        /** 
         * Abstraction layer for util
         * @return mixed Class WP_Util instance
         * @since 1.0.0
         */
        public function wp_util()
        {
            return Jodacame_WP_Util::instance();
        }

        /** 
         * Abstraction layer for menu
         * @return mixed Class WP_Menu instance
         * @since 1.0.0
         */
        public function wp_menu($id = null)
        {
            return Jodacame_WP_Menu::instance($id);
        }

        /** 
         * Abstraction layer for metabox
         * @return mixed Class WP_Metabox instance
         * @since 1.0.0
         */
        public function wp_metabox($id = null)
        {
            return Jodacame_WP_Metabox::instance($id);
        }

        /** 
         * Abstraction layer for nonce
         * @return mixed Class WP_Nonce instance
         * @since 1.0.0
         */
        public function wp_nonce($id = null)
        {
            return Jodacame_WP_Nonce::instance($id);
        }

        /** 
         * Abstraction layer for wp_remote
         * @return mixed Class WP_Request instance
         * @since 1.0.0
         */
        public function wp_request($url)
        {
            return Jodacame_WP_Request::instance($url);
        }

        /** 
         * Abstraction layer for wp_rest
         * @return mixed Class WP_Rest instance
         * @since 1.0.0
         */
        public function wp_rest()
        {
            return Jodacame_WP_Rest::instance();
        }
    }
}

//
