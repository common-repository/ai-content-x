<?php
if (!class_exists('Jodacame_WP_Db'))
{
    class Jodacame_WP_Db
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


        /**
         * Create table
         * @param string $table table name
         * @param array $fields table fields
         * @return void
         * @since 1.0.0
         */
        public function create($table, $fields, $pk = 'id')
        {
            global $wpdb;
            $table = $wpdb->prefix . $table;
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE IF NOT EXISTS $table (";
            foreach ($fields as $field => $type)
            {
                $sql .= "$field $type,";
            }
            $sql .= "PRIMARY KEY  ($pk)
            ) $charset_collate;";
            if ($wpdb->query($sql) === false)
            {
                return false;
            }
            return true;
        }

        /** 
         * Get table fields
         * @param string $table table name
         * @return  int last insert id
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/classes/wpdb/#insert-row
         */

        public function insert($table, $data)
        {
            global $wpdb;
            $table = $wpdb->prefix . $table;
            if ($wpdb->insert($table, $data))
            {
                return $wpdb->insert_id;
            }
            return false;
        }

        /**
         * Truncate table (Delete all rows)
         * @param string $table table name
         * @return void
         * @since 1.0.0
         * @access public
         */

        public function truncate($table)
        {
            global $wpdb;
            $table = $wpdb->prefix . $table;
            $wpdb->query("TRUNCATE TABLE $table");
        }

        /**
         * Drop table
         * @param string $table table name
         * @return void
         * @since 1.0.0
         * @access public
         */

        public function drop($table)
        {
            global $wpdb;
            $table = $wpdb->prefix . $table;
            $wpdb->query("DROP TABLE $table");
        }

        /**
         * Get table fields
         * @param array $options table options (table, fields, where, order, limit)
         * @return array table fields
         */
        public function get($options = array())
        {
            global $wpdb;
            $table = $options['table'];
            $table = $wpdb->prefix . $table;
            $fields = isset($options['fields']) ? $options['fields'] : '*';
            $where = isset($options['where']) ? "WHERE " . $options['where'] : '';
            $order = isset($options['order']) ? "ORDER BY " . $options['order'] : '';
            $limit = isset($options['limit']) ? "LIMIT " . $options['limit'] : '';
            $sql = "SELECT $fields FROM $table $where $order $limit";
            return $wpdb->get_results($sql);
        }
    }
}
