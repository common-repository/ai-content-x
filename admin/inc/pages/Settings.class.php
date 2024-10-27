<?php

if (!class_exists('AICX_Pro_Page_Settings'))
{
    /**
     * Class to display Settings page in admin panel
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Page_Settings extends Jodacame
    {
        private $option_name = 'ai_content_x';

        public function __construct()
        {
            $this->hooks();
        }

        public function hooks()
        {

            $this->wp_setting($this->option_name)->register($this->option_name, array($this, 'sanitize'));
        }

        public function page()
        {
            if (!$this->wp_user('me')->can('manage_options'))
            {
                return;
            }

            echo '<div class="wrap ai-content-x-page">';
            echo $this->ui()->form_open(array('data-key' => $this->option_name, 'onsubmit' => 'return aicxpro.options.save(this)'));
            $this->load_options();
            $this->wp_setting($this->option_name)->fields(true);
            echo $this->ui()->form_close();
            echo '</div>';
        }

        public function load_options()
        {

            // Add sections tabs 
            $tabs = array(
                'general' => __('General', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'task' => __('Task', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'other' => __('Others', AICX_PRO_PLUGIN_TEXT_DOMAIN),
            );
            $tabs_titles = array(
                'general' => __('General Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'task' => __('Task Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'other' => __('Other Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN),
            );

            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';


            $tab_content = $this->ui()->image(
                array(
                    'src' => AICX_PRO_PLUGIN_URL . 'assets/img/icon-color-64.png',
                    'alt' => 'AI Content X',
                )
            );

            $tab_content .= $this->ui()->tag(
                'div',
                __($tabs_titles[$active_tab], AICX_PRO_PLUGIN_TEXT_DOMAIN),
                array(
                    'class' => 'title',
                )
            );


            foreach ($tabs as $tab => $name)
            {
                $class = ($tab == $active_tab) ? ' nav-tab-active' : '';

                $tab_content .= $this->ui()->link(
                    $name,
                    '?page=ai-content-x&tab=' . $tab,
                    array(
                        'class' => 'nav-tab' . $class,
                    )
                );
            }

            $tab_content .= $this->ui()->tag(
                'div',
                false,
                array(
                    'class' => 'ai-content-is-spacer',
                )
            );

            $tab_content .= $this->ui()->link(
                __('Upgrade Pro', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                'https://jodacame.dev/ai-content-x/free-vs-pro/',
                array(
                    'class' => 'ai-content-button ai-content-primary',
                    'target' => '_blank',
                )
            );

            $tab_content .= $this->ui()->button(
                __('Save Changes', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                array(
                    'class' => 'ai-content-button ai-content-dark',
                    'type' => 'submit',
                )
            );



            echo $this->ui()->tag(
                'div',
                $tab_content,
                array(
                    'class' => 'nav-tab-wrapper ai-content-tabs',
                )
            );




            switch ($active_tab)
            {
                case 'general':
                    $this->general_tab();
                    break;
                case 'task':
                    $this->task_tab();
                    break;
                case 'other':
                    $this->other_tab();
                    break;
            }
        }

        public function general_tab()
        {
            // General section
            $this->wp_setting('general')->add_section(false, array($this, 'section_callback'), 'ai_content_x');

            $this->wp_setting('general')->add_field('prompt', __('Prompt', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'prompt_callback'), 'ai_content_x');
            $this->wp_setting('general')->add_field('new_status_post', __('New Status Post', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'new_status_post_callback'), 'ai_content_x');
            $this->wp_setting('general')->add_field('extract_title', __('Extract Title Automatically', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'extract_title_callback'), 'ai_content_x');
        }
        public function task_tab()
        {
            // Task section
            $this->wp_setting('task')->add_section(false, array($this, 'section_callback'), 'ai_content_x');
            $this->wp_setting('task')->add_field('cronjob', __('Run Task Every ? Minutes', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'cronjob_callback'), 'ai_content_x');
            $this->wp_setting('task')->add_field('cronjob_number_post', __('Max number post per process', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'cronjob_number_post_callback'), 'ai_content_x');
            $this->wp_setting('task')->add_field('cronjob_post_status', __('Post Status', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'cronjob_post_status_callback'), 'ai_content_x');
            $this->wp_setting('task')->add_field('cronjob_post_type', __('Post Type', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'cronjob_post_type_callback'), 'ai_content_x');
            $this->wp_setting('task')->add_field('email_notification', __('Email Notification', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'email_notification_callback'), 'ai_content_x');
        }

        public function other_tab()
        {
            // Other section
            $this->wp_setting('other')->add_section(false, array($this, 'section_callback'), 'ai_content_x');
            $this->wp_setting('other')->add_field('other_delete_data_uninstall', __('Delete Data on Uninstall', AICX_PRO_PLUGIN_TEXT_DOMAIN), array($this, 'other_delete_data_uninstall_callback'), 'ai_content_x');
        }



        public function section_callback()
        {
            echo $this->ui()->link(
                "<img src='" . AICX_PRO_PLUGIN_URL . "assets/img/icon-color-64.png' alt='Documentation' />"
                    . __('Documentation', AICX_PRO_PLUGIN_TEXT_DOMAIN)
                    . '<small style="margin-left:5px">'
                    . __("Ver.", AICX_PRO_PLUGIN_TEXT_DOMAIN)
                    . AICX_PRO_PLUGIN_VERSION
                    . '</small>',
                'https://jodacame.dev/kb/ai-content-x/',
                array(
                    'target' => '_blank',
                    'class' => 'ai-content-x-pro-doc-float'
                )
            );
        }

        // Prompt
        public function prompt_callback()
        {
            // $options = get_option('ai_content_x');
            // $prompt = $options['prompt'] ?? '';
            // print all methods of this class

            $prompt = $this->wp_option('ai_content_x')->get('prompt');

            echo $this->ui()->textarea(
                'ai_content_x[prompt]',
                $prompt,
                array(
                    'rows' => 5,
                    'style' => 'width:100%'
                )
            );

            echo $this->ui()->description(__("Use custom vars to replace with post title or content the prompt. Available vars: <code>%post_title%</code> <code>%post_content%</code>.", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }

        // New Status Post
        public function new_status_post_callback()
        {

            $new_status_post = $this->wp_option('ai_content_x')->get('new_status_post', '');

            $status_post = $this->wp_post()->statuses();
            echo $this->ui()->select(
                'ai_content_x[new_status_post]',
                $status_post,
                $new_status_post,
                array(
                    'class' => 'regular-text'
                )
            );
            echo $this->ui()->description(__("Set status post after update post", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }

        public function extract_title_callback()
        {

            $extract_title = $this->wp_option('ai_content_x')->get('extract_title', '');

            echo $this->ui()->checkbox(
                'ai_content_x[extract_title]',
                1,
                $extract_title,
                array(
                    'class' => 'ai-content-x-input'
                )

            );

            echo $this->ui()->description(__("Extract title from content if exists. Extract first h1 and set it as title", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }

        // Cronjob Section
        public function cronjob_section_callback()
        {
        }

        // Cronjob
        public function cronjob_callback()
        {
            $cronjob = $this->wp_option('ai_content_x')->get('cronjob', 0);
            echo $this->ui()->input(
                'number',
                'ai_content_x[cronjob]',
                $cronjob,
                array(
                    'min' => 0,
                    'max' => 99999,
                    'required' => true
                )
            );
            echo $this->ui()->description(__("Set 0 to disable cronjob. Cronjob will run every X minutes", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }



        // Cronjob number post
        public function cronjob_number_post_callback()
        {
            // $options = get_option('ai_content_x');
            // $cronjob_number_post = $options['cronjob_number_post'] ?? '';
            $cronjob_number_post = $this->wp_option('ai_content_x')->get('cronjob_number_post', 1);
            echo $this->ui()->input(
                'number',
                'ai_content_x[cronjob_number_post]',
                $cronjob_number_post,
                array(
                    'min' => 1,
                    'max' => 20,
                    'required' => true
                )
            );
            echo $this->ui()->description(__("How many post will be prcessed at a time by cronjob. don't set too high number to avoid server overload. We recommend 1", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }

        // Cronjob Post Status
        public function cronjob_post_status_callback()
        {

            $cronjob_post_status = $this->wp_option('ai_content_x')->get('cronjob_post_status', 'draft');
            $status_post = $this->wp_post()->statuses();

            echo $this->ui()->select(
                'ai_content_x[cronjob_post_status]',
                $status_post,
                $cronjob_post_status,
            );
            echo $this->ui()->description(__("Select only post with this status", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }

        // cronjob post type
        public function cronjob_post_type_callback()
        {
            echo $this->ui()->premium();
            echo $this->ui()->description(__("Select only post with this post type", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }
        public function email_notification_callback()
        {


            echo $this->ui()->premium();
            echo $this->ui()->description(__("Set email to receive notification when content is updated or error occurs, leave empty to disable", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }

        public function other_section_callback()
        {
        }
        public function other_delete_data_uninstall_callback()
        {

            $delete_data_uninstall = $this->wp_option('ai_content_x')->get('delete_data_uninstall', 2);
            echo $this->ui()->select(
                'ai_content_x[delete_data_uninstall]',
                array(
                    0 => __('Keep all data', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                    1 => __('Delete all data', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                    2 => __('Delete only API Key and keep all data', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                ),
                $delete_data_uninstall,
            );
            echo $this->ui()->description(__("Select what data you want to delete when you uninstall the plugin", AICX_PRO_PLUGIN_TEXT_DOMAIN));
        }
    }
}
