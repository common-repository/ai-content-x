<?php
if (!class_exists('Jodacame_WP_Request'))
{
    class Jodacame_WP_Request
    {
        private static $_instance = null;
        private $url = null;

        public static function instance($url)
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            self::$_instance->url = $url;
            return self::$_instance;
        }

        /**
         * Make a POST request to a remote server
         * @param array $args
         * @return array
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/wp_remote_post/
         */

        public function post($args)
        {
            $args = wp_parse_args($args, array(
                'data' => array(),
                'headers' => array(),
                'timeout' => 30,
                'sslverify' => false,
                'blocking' => true,
                'cookies' => array(),
                'user-agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url'),
                'compress' => false,
                'decompress' => true,
                'stream' => false,
                'filename' => null,
                'limit_response_size' => null,
            ));

            $response =  wp_remote_post($this->url, $args);

            if (is_wp_error($response))
            {

                return array(
                    'status' => 'error',
                    'message' => $response->get_error_message()
                );
            }

            return array(
                'status' => 'success',
                'data' => wp_remote_retrieve_body($response)
            );
        }
    }
}
