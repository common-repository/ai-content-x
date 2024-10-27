<?php
if (!class_exists('Jodacame_WP_Options'))
{
    class Jodacame_WP_Options
    {
        private static $_instance = null;
        private $option = null;

        public static function instance($option)
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            self::$_instance->option = $option;
            return self::$_instance;
        }

        /**
         * Get all options from WordPress
         * @return array
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_option/
         */

        public function all()
        {
            $options = get_option($this->option);
            return $options;
        }

        /**
         * Get option by key from WordPress
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_option/
         */

        public function get($key = null, $default = null)
        {
            if (!$key) return $this->all();

            $options = get_option($this->option);
            return isset($options[$key]) ? $options[$key] : $default;
        }

        /**
         * Update option to WordPress
         * @param array $options
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/update_option/
         */

        public function set($options)
        {
            update_option($this->option, $options);
        }


        /**
         * Add option to WordPress
         * @param array $options
         * @return void
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/add_option/
         */
        public function add($options)
        {
            add_option($this->option, $options);
        }


        public function sanitize_text_field($value)
        {
            return sanitize_text_field($value);
        }
    }
}
