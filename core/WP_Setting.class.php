<?php
if (!class_exists('Jodacame_WP_Settings'))
{
    class Jodacame_WP_Settings
    {
        private static $_instance = null;
        private $id = null; // Identifier

        /** 
         * Constructor
         * @param string $id identifier 
         * @return void
         * @since 1.0.0
         */
        public static function instance($id)
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            self::$_instance->id = $id;
            return self::$_instance;
        }

        /** 
         * Add a section to the settings
         * @param string $title section title
         * @param callable $callback callback function
         * @param string $page page where the section will be displayed
         * @param array $args arguments
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/add_settings_section/
         * 
         */
        public function add_section($title, $callback, $page, $args = array())
        {
            add_settings_section($this->id, $title, $callback, $page, $args);
        }

        /**
         * Add a field to the settings
         * @param string $id field id
         * @param string $title field title
         * @param callable $callback callback function
         * @param string $page page where the field will be displayed
         * @param array $args arguments
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/add_settings_field/
         * 
         */
        public function add_field($id, $title, $callback, $page, $args = array())
        {
            add_settings_field($id, $title, $callback, $page, $this->id, $args);
        }

        /**
         * Generate fields for the settings 
         * @param bool $print if true, print the fields
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/settings_fields/
         * @link https://developer.wordpress.org/reference/functions/do_settings_sections/
         * 
         */

        public function fields($print = true)
        {
            settings_fields($this->id);
            if ($print)
            {
                $this->print();
            }
        }

        /**
         * Print the fields
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/do_settings_sections/
         * 
         */
        public function print()
        {
            do_settings_sections($this->id);
        }

        /**
         * Register a setting
         * @param string $option_name option name
         * @param array $args arguments
         * @return void
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/register_setting/
         */

        public function register($option_name, $args = array())
        {
            register_setting($this->id, $option_name, $args);
        }
    }
}
