<?php
if (!class_exists('AICX_Pro_Defaults'))
{
    /**
     * Manage default settings and tables
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Defaults extends Jodacame
    {
        public function __construct()
        {
            $this->hooks();
        }

        /**
         * Add hooks
         */

        public function hooks()
        {
            $this->wp_hook()->register_activation(AICX_PRO_PLUGIN_FILE_URL, array($this, 'default_settings'));
            $this->wp_hook()->register_deactivation(AICX_PRO_PLUGIN_FILE_URL, array($this, 'delete_settings'));
            $this->wp_hook()->add_action('ai_content_x_clear_log', array($this, 'truncate_table_logs'));
        }

        /**
         * On activating the plugin, create default settings
         * @return void
         */

        public function default_settings()
        {

            $default_settings = array(
                'ai_content_x_openai_api_key' => '',
                'ai_content_x_openai_model' => 'text-davinci-003',
                'ai_content_x_openai_max_tokens' => 1000,
                'ai_content_x_openai_temperature' => 0.9,
                'ai_content_x_openai_top_p' => 1,
                'ai_content_x_openai_frequency_penalty' => 0,
                'ai_content_x_openai_presence_penalty' => 0,
                'ai_content_x_openai_stop_sequence' => '',
            );
            $this->wp_option('ai_content_x_openai')->add($default_settings);

            $default_settings = array(
                'prompt' => 'Write a 500-word article about %post_title% that includes at least 3 subheadings (h2 tags), 2 bullet point lists (ul tags), and 3 instances of bolded text (strong tags). Use relevant keywords throughout. The article should be written in HTML format, with headings and paragraphs.',
                'new_status_post' => 'publish',
                'cronjob_post_status' => 'draft',
                'cronjob_number_post' => 1,
                'cronjob' => 60,
                'cronjob_post_type' => 'post',
                'delete_data_uninstall' => '2',
            );

            $this->wp_option('ai_content_x')->add($default_settings);

            $this->create_table_logs();
        }

        /**
         * On deactivating the plugin, delete settings and logs if selected in settings page
         * @return void
         */

        public function delete_settings()
        {

            $options = get_option('ai_content_x');
            $delete_data_uninstall = intval($options['delete_data_uninstall']);

            // Do nothing
            if ($delete_data_uninstall === 0)
            {
                return;
            }

            // Delete all data
            if ($delete_data_uninstall === 1)
            {
                delete_option('ai_content_x');
                delete_option('ai_content_x_openai');
                $this->delete_table_logs();

                $args = array(
                    'post_type' => AICX_PRO_PLUGIN_POST_TYPE,
                    'post_status' => 'any',
                    'posts_per_page' => -1,
                );
                $posts = get_posts($args);
                foreach ($posts as $post)
                {
                    wp_delete_post($post->ID, true);
                }
            }

            // Delete only api key
            if ($delete_data_uninstall === 2)
            {
                $options = get_option('ai_content_x_openai');
                $options['ai_content_x_openai_api_key'] = '';
                update_option('ai_content_x_openai', $options);
            }

            $options = get_option('ai_content_x');

            update_option('ai_content_x', $options);
        }

        /**
         * Create table for logs if not exists
         * @return void
         */

        public function create_table_logs()
        {
            // global $wpdb;
            // $table_name = $wpdb->prefix . 'ai_content_x_logs';
            // $charset_collate = $wpdb->get_charset_collate();
            // $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            //             id int(11) NOT NULL AUTO_INCREMENT,
            //             type varchar(255) NOT NULL,
            //             post_id int(11) NOT NULL,
            //             prompt text,
            //             message text,
            //             created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            //             PRIMARY KEY  (id)
            //         ) $charset_collate;";
            // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            // dbDelta($sql);

            $this->wp_db()->create(
                'ai_content_x_logs',
                array(
                    'id' => 'int(11) NOT NULL AUTO_INCREMENT',
                    'type' => 'varchar(255) NOT NULL',
                    'post_id' => 'int(11) NOT NULL',
                    'prompt' => 'text',
                    'message' => 'text',
                    'created_at' => 'datetime NOT NULL DEFAULT CURRENT_TIMESTAMP',
                ),
                'id'
            );
        }

        /**
         * Truncate table for logs
         * @return void
         */

        public function truncate_table_logs()
        {
            $this->wp_db()->truncate('ai_content_x_logs');
        }

        /**
         * Delete table for logs
         * @return void
         */

        public function delete_table_logs()
        {
            $this->wp_db()->drop('ai_content_x_logs');
        }
    }
}
