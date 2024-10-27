<?php

if (!class_exists('AICX_Pro_Language'))
{
    /**
     * AICX Pro Language
     * @description: Load plugin text domain
     * @since 1.6.0
     * @package 
     * @category Language
     */

    class AICX_Pro_Language extends Jodacame
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
            $this->wp_hook()->add_action('plugins_loaded', array($this, 'load_textdomain'));
        }

        /**
         * Load plugin text domain
         */
        public function load_textdomain()
        {
            $this->wp_util()->load_plugin_textdomain(AICX_PRO_PLUGIN_TEXT_DOMAIN, false, dirname(AICX_PRO_PLUGIN_BASENAME) . '/languages/');
        }
    }
}
