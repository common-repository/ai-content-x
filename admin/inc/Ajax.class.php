<?php

if (!class_exists('AICX_Pro_Ajax'))
{
    /**
     * Manage Ajax requests
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Ajax extends Jodacame
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
            $this->wp_hook()->add_action('wp_ajax_ai_content_x_run_cron', array($this, 'run_task'));
            $this->wp_hook()->add_action('wp_ajax_ai_content_x_clear_log', array($this, 'clear_log'));
            $this->wp_hook()->add_action('wp_ajax_ai_content_x_save_options', array($this, 'save_options'));
        }


        /**
         * Allow admin to clear log table from admin via ajax
         * @return void
         */

        public function clear_log()
        {
            if (!$this->wp_user('me')->can('manage_options'))
            {
                return;
            }

            if (!isset($_POST['nonce']))
            {
                $this->wp_util()->die('Error, nonce not set');
                return;
            }
            if (!$this->wp_nonce()->verify($_POST['nonce'], 'wp_create_nonce'))
            {
                $this->wp_util()->die('Error, nonce not verified');
                return;
            }

            $this->wp_hook()->do_action('ai_content_x_clear_log');

            $this->wp_util()->die('success');
        }

        /**
         * Allow admin to run cron task from admin via ajax
         * @return void
         */

        public function run_task()
        {

            if (!$this->wp_user('me')->can('manage_options'))
            {
                return;
            }

            if (!isset($_POST['nonce']))
            {
                $this->wp_util()->wp_die('Error, nonce not set');
                return;
            }
            if (!$this->wp_nonce()->verify($_POST['nonce'], 'wp_create_nonce'))
            {
                $this->wp_util()->wp_die('Error, nonce not verified');
                return;
            }
            $this->wp_hook()->do_action('aicx_pro_task');

            $this->wp_util()->die('success');
        }

        public function save_options()
        {
            if (!$this->wp_user('me')->can('manage_options'))
            {
                return;
            }

            if (!isset($_POST['nonce']))
            {
                $this->wp_util()->die('Error, nonce not set');
                return;
            }
            if (!$this->wp_nonce()->verify($_POST['nonce'], 'wp_create_nonce'))
            {
                $this->wp_util()->die('Error, nonce not verified');
                return;
            }

            $key = $_POST['key'];


            if (!$key)
            {
                $this->wp_util()->die('Error, key not set');
            }

            $options = $_POST[$key];

            if (!$options)
            {
                $this->wp_util()->die('Error, options not set');
            }

            $options = array_map('sanitize_text_field', $options);


            $all_options = $this->wp_option($key)->get();

            if ($all_options)
            {
                $options = array_merge($all_options, $options);
            }

            $this->wp_option($key)->set($options);

            $this->wp_util()->die('success');
        }
    }
}
