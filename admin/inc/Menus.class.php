<?php
if (!class_exists('AICX_Pro_Menus'))
{


    /**
     * Load all pages classes, required for admin menus
     */

    require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/pages/Logs.class.php';
    require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/pages/Settings.class.php';
    require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/pages/OpenAI-Settings.class.php';

    /**
     * Class to display menu AI Content X in admin panel with icon and submenus
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Menus extends Jodacame
    {

        private $page_settings = null;
        private $page_openai_settings = null;
        private $page_logs = null;


        public function __construct()
        {
            $this->load_pages_classes();
            $this->hooks();
        }

        /**
         * Load all pages classes
         */

        public function load_pages_classes()
        {
            $this->page_settings = new AICX_Pro_Page_Settings();
            $this->page_openai_settings = new AICX_Pro_Page_OpenAI_Settings();
            $this->page_logs = new AICX_Pro_Page_Logs();
        }

        /**
         * Add hooks
         */

        public function hooks()
        {
            $this->wp_hook()->add_filter('plugin_row_meta', array($this, 'documentation_link'), 10, 2);
            $this->wp_hook()->add_filter('plugin_action_links_' . AICX_PRO_PLUGIN_BASENAME, array($this, 'settings_link'));
            $this->wp_hook()->add_action('admin_menu', array($this, 'admin_menu'));
        }

        /**
         * Add documentation link to plugin page
         */
        public function documentation_link($links, $file)
        {
            if ($file === AICX_PRO_PLUGIN_BASENAME)
            {
                $row_meta = array(
                    'support' => '<a href="admin.php?page=ai-content-x-openai-settings" class="ai-content-x-link-doc"><span>'
                        . '<img src="' . AICX_PRO_PLUGIN_URL . 'assets/img/icon-color-64.png"  alt="Support" />'
                        . __('OpenAI Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN) . '</span></a>',
                    'documentation' => '<a href="https://jodacame.dev/kb/ai-content-x/" target="_blank" class="ai-content-x-link-doc"><span>'
                        . '<img src="' . AICX_PRO_PLUGIN_URL . 'assets/img/icon-color-64.png"  alt="Documentation" />'
                        . __('Documentation & Support', AICX_PRO_PLUGIN_TEXT_DOMAIN) . '</span></a>',

                );
                return array_merge($links, $row_meta);
            }
            return (array) $links;
        }

        /**
         * Add settings link to plugin page
         */

        public function settings_link($links)
        {
            $settings_link = '<a href="options-general.php?page=' . AICX_PRO_PLUGIN_SLUG . '">' . __('Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN) . '</a>';
            array_unshift($links, $settings_link);
            $settings_link = '<a href="https://jodacame.dev/ai-content-x/free-vs-pro/" target="_blank" style="font-weight: bold; color: #d64e07;">' . __('Upgrade Pro', AICX_PRO_PLUGIN_TEXT_DOMAIN) . '</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        /** 
         * Function to display menu AI Content X in admin panel with icon and submenus 
         */

        public function admin_menu()
        {
            // Create Main Menu
            $this->wp_menu()->create(
                AICX_PRO_PLUGIN_NAME,
                'manage_options',
                AICX_PRO_PLUGIN_SLUG,
                array($this->page_settings, 'page'),
                AICX_PRO_PLUGIN_URL . 'icon.svg',
                5
            );

            $this->wp_menu(AICX_PRO_PLUGIN_SLUG)->add(
                __('Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'manage_options',
                AICX_PRO_PLUGIN_SLUG,
                array($this->page_settings, 'page'),
            );


            $this->wp_menu(AICX_PRO_PLUGIN_SLUG)->add(
                __('OpenAI Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'manage_options',
                'ai-content-x-openai-settings',
                array($this->page_openai_settings, 'page'),
            );


            $this->wp_menu(AICX_PRO_PLUGIN_SLUG)->add(
                __('Queue & Logs', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'manage_options',
                'ai-content-x-logs',
                array($this->page_logs, 'page'),
            );

            $this->wp_menu(AICX_PRO_PLUGIN_SLUG)->add(
                __('Prompts', AICX_PRO_PLUGIN_TEXT_DOMAIN) . " " . $this->ui()->premium('PRO', false, 'right'),
                'manage_options',
                'https://jodacame.dev/ai-content-x/free-vs-pro/',
            );
            $this->wp_menu(AICX_PRO_PLUGIN_SLUG)->add(
                "<strong style='color: #d64e07;'>" . __('Upgrade Pro', AICX_PRO_PLUGIN_TEXT_DOMAIN) . "</strong>",

                'manage_options',
                'https://jodacame.dev/ai-content-x/free-vs-pro/',
            );
        }
    }
}
