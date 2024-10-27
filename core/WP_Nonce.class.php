<?php
if (!class_exists('Jodacame_WP_Nonce'))
{
    class Jodacame_WP_Nonce
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
         * Wordpress create nonce function
         * @param string $action action
         * @return string
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/wp_create_nonce/
         */

        public function create($action = -1)
        {
            return wp_create_nonce($action);
        }


        /** 
         * Wordpress verify nonce function
         * @param string $nonce nonce
         * @param string $action action
         * @return bool
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/wp_verify_nonce/
         */

        public function verify($nonce, $action = -1)
        {
            return wp_verify_nonce($nonce, $action);
        }

        public function field($action = -1, $name = '_wpnonce')
        {
            wp_nonce_field($action, $name);
        }
    }
}
