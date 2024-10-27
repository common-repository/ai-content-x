<?php
if (!class_exists('Jodacame_WP_Scheduled'))
{
    class Jodacame_WP_Scheduled
    {
        private static $_instance = null;
        private $hook = null; // Identifier
        /** 
         * Constructor
         * @param string $id identifier 
         * @return void
         * @since 1.0.0
         */
        public static function instance($hook = null)
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            self::$_instance->hook = $hook;
            return self::$_instance;
        }

        public function next($args = array())
        {
            return wp_next_scheduled($this->hook, $args);
        }

        /**
         * Creates a new event.
         * @param int $timestamp Unix timestamp (UTC) for when to run the event.
         * @param string $recurrence How often the event should recur. See wp_get_schedules() for accepted values.
         * @param array $args Optional. Arguments to pass to the hook
         * @return bool False if event could not be scheduled, true otherwise.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/wp_schedule_event/
         */
        public function create($timestamp, $recurrence, $args = array())
        {
            return wp_schedule_event($timestamp, $recurrence, $this->hook, $args);
        }

        /**
         * Unschedules all events attached to the hook with the specified arguments.
         * @param array $args Optional. Arguments to pass to the hook
         * @return bool False if event could not be unscheduled, true otherwise.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/wp_clear_scheduled_hook/
         */

        public function delete($args = array())
        {
            return wp_clear_scheduled_hook($this->hook, $args);
        }
    }
}
