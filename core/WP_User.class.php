<?php
if (!class_exists('Jodacame_WP_User'))
{
    class Jodacame_WP_User
    {
        private static $_instance = null;
        private $id = null; // User Identifier
        /** 
         * Constructor
         * @return void
         * @since 1.0.0
         */
        public static function instance($id = 'me')
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            self::$_instance->id = $id;
            return self::$_instance;
        }
        /** 
         * Check if the current user has a capability
         * @param string $capability capability
         * @return bool
         * @since 1.0.0
         * @link https://developer.wordpress.org/reference/functions/current_user_can/
         * @link https://developer.wordpress.org/reference/functions/get_user_by/
         * @link https://developer.wordpress.org/reference/functions/user_can/
         */
        public function can($capability, $args = null)
        {
            if ($this->id == 'me')
            {
                return current_user_can($capability, $args);
            }
            else
            {
                $user = get_user_by('id', $this->id);
                return $user->has_cap($capability, $args);
            }
        }

        public function is_super_admin()
        {
            if ($this->id == 'me')
            {
                return is_super_admin();
            }
            else
            {
                return is_super_admin($this->id);
            }
        }
    }
}
