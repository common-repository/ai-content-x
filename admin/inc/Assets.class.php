<?php

if (!class_exists('AICX_PRO_Assets'))
{
    /**
     * Load all admin assets
     * @since 1.6.0
     * @package AI Content X Pro
     * @category Admin
     */

    class AICX_PRO_Assets extends Jodacame
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
            $this->wp_hook()->add_action('admin_enqueue_scripts', array($this, 'ai_content_x_admin_scripts'));
            $this->wp_hook()->add_action('admin_enqueue_scripts', array($this, 'ai_content_x_admin_styles'));
        }

        /**
         * Load admin scripts
         * @return void
         */

        public function ai_content_x_admin_scripts()
        {
            $this->wp_util()->enqueue_script('ai-content-x-admin-scripts', AICX_PRO_PLUGIN_URL . 'assets/js/script.js', array('jquery'), AICX_PRO_PLUGIN_VERSION);
            $this->wp_util()->localize_script('ai-content-x-admin-scripts', 'ajax_var', array(
                'url'    => $this->wp_util()->admin_url('admin-ajax.php'),
                'nonce'  => $this->wp_nonce()->create('wp_create_nonce'),
                'run' => 'ai_content_x_run_cron',
                'save_options' => 'ai_content_x_save_options',

            ));
        }

        /**
         * Load admin styles
         * @return void
         */

        public function ai_content_x_admin_styles()
        {
            $this->wp_util()->enqueue_style('ai-content-x-admin-styles', AICX_PRO_PLUGIN_URL . 'assets/css/style.css', array(), AICX_PRO_PLUGIN_VERSION);
        }
    }
}
