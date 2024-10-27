<?php
if (!class_exists('AICX_Pro_Notice'))
{

    /**
     * Manage admin notices
     * @since      1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Notice extends Jodacame
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
            $this->wp_hook()->add_action('admin_notices', array($this, 'settings_updated'));
            $this->wp_hook()->add_action('admin_notices', array($this, 'api_key_required'));
        }

        /**
         * Show settings updated notice
         */
        public function settings_updated()
        {
            if (!isset($_GET['page']))
            {
                return;
            }


            if (isset($_GET['settings-updated']) && ($_GET['page'] == 'ai-content-x-openai-settings' || $_GET['page'] == 'ai-content-x-pro'))
            {
                echo '<div class="notice notice-success is-dismissible ai-content-x-notice">
                    <img src="' . AICX_PRO_PLUGIN_URL . 'assets/img/icon-color-64.png">
                     <p>' . __('Settings Updated', AICX_PRO_PLUGIN_TEXT_DOMAIN) . '</p>
                 </div>';
            }
        }

        /**
         * Show API key required notice
         */
        public function api_key_required()
        {
            $openai_api_key = $this->wp_option('ai_content_x_openai')->get('ai_content_x_openai_api_key');

            if ($openai_api_key)
            {
                return;
            }
            echo '<div class="notice notice-error is-dismissible ai-content-x-notice">
                    <img src="' . AICX_PRO_PLUGIN_URL . 'assets/img/icon-color-64.png">
                     <p>' . __('OpenAI API Key is required', AICX_PRO_PLUGIN_TEXT_DOMAIN) . '</p>
                     <p><a href="admin.php?page=ai-content-x-openai-settings">' . __('OpenAI Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN) . '</a></p>
                 </div>';
        }
    }
}
