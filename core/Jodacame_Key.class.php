<?php
if (!class_exists('Jodacame_Key'))
{
    class Jodacame_Key
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
    }
}
