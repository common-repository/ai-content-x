<?php

if (!class_exists('AICX_Pro_Page_OpenAI_Settings'))
{

    /**
     * Class to display OpenAI Settings page in admin panel
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Page_OpenAI_Settings extends Jodacame
    {
        /* @var string $option_name */
        private $option_name = 'ai_content_x_openai';

        public function __construct()
        {
            $this->hooks();
        }

        /**
         * Add hooks
         */

        public function hooks()
        {
            $this->wp_setting($this->option_name)->register($this->option_name, array($this, 'sanitize'));
        }


        /**
         * Display OpenAI Settings page
         */

        public function page()
        {
            // Check user capabilities
            if (!$this->wp_user('me')->can('manage_options'))
            {
                return;
            }




?>
            <div class="wrap ai-content-x-page">

                <form action="options.php" data-key="<?php echo $this->option_name; ?>" onsubmit="return aicxpro.options.save(this)">
                    <?php

                    $this->options();

                    // settings_fields($this->option_name);
                    $this->wp_setting($this->option_name)->fields(true);

                    // do_settings_sections($this->option_name);

                    ?>
                </form>
            </div>
<?php
        }


        public function options()
        {


            $tab_content = $this->ui()->image(
                array(
                    'src' => AICX_PRO_PLUGIN_URL . 'assets/img/icon-color-64.png',
                    'alt' => 'AI Content X',
                )
            );

            $tab_content .= $this->ui()->tag(
                'div',
                __('OpenAI Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                array(
                    'class' => 'title',
                )
            );


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


            $this->general_tab();
        }

        public function general_tab()
        {
            $this->wp_setting($this->option_name)->add_section(null, array($this, 'section_callback'), $this->option_name);
            $this->wp_setting($this->option_name)->add_field('api_key', 'API Key', array($this, 'api_key_callback'), $this->option_name, 'section');
            $this->wp_setting($this->option_name)->add_field('model', 'Model', array($this, 'model_callback'), $this->option_name, 'section');
            $this->wp_setting($this->option_name)->add_field('max_tokens', 'Max Tokens', array($this, 'max_tokens_callback'), $this->option_name, 'section');
            $this->wp_setting($this->option_name)->add_field('temperature', 'Temperature', array($this, 'temperature_callback'), $this->option_name, 'section');
            $this->wp_setting($this->option_name)->add_field('top_p', 'Top P', array($this, 'top_p_callback'), $this->option_name, 'section');
            $this->wp_setting($this->option_name)->add_field('frequency_penalty',  'Frequency Penalty', array($this, 'frequency_penalty_callback'), $this->option_name, 'section');
            $this->wp_setting($this->option_name)->add_field('presence_penalty', 'Presence Penalty', array($this, 'presence_penalty_callback'), $this->option_name, 'section');
            $this->wp_setting($this->option_name)->add_field('stop_sequence',  'Stop Sequence', array($this, 'stop_sequence_callback'), $this->option_name, 'section');
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
                'https://jodacame.dev/kb/ai-content-x-pro/',
                array(
                    'target' => '_blank',
                    'class' => 'ai-content-x-pro-doc-float'
                )
            );
        }
        public function api_key_callback()
        {
            // $options = get_option($this->option_name);
            $ai_content_x_openai_api_key = $this->wp_option($this->option_name)->get('ai_content_x_openai_api_key', '');
            echo $this->ui()->input(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_api_key]',
                    'value' => $ai_content_x_openai_api_key,
                    'type' => 'password',
                    'required' => true,
                    'style' => 'width:100%;'
                )
            );
            echo $this->ui()->description(
                __('<p>Enter your OpenAI API Key and select your Engine, Model, and other settings.</p>', AICX_PRO_PLUGIN_TEXT_DOMAIN) .
                    __('To get an API key, <a href="https://beta.openai.com/account/api-keys" target="_blank">sign up for an OpenAI account</a>.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }

        public function model_callback()
        {
            $models = AICX_PRO_MODELS;
            $models = array_combine($models, $models);


            // $options = get_option($this->option_name);
            $ai_content_x_openai_model = $this->wp_option($this->option_name)->get('ai_content_x_openai_model', '');
            echo $this->ui()->select(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_model]',
                    'value' => $ai_content_x_openai_model,
                    'selected' => $ai_content_x_openai_model,
                    'options' => $models,
                    'required' => true,
                )
            );

            echo $this->ui()->description(
                __('Select a model from the dropdown menu. For more information about the models, see the <a href="https://beta.openai.com/docs/models/gpt-3" target="_blank">OpenAI documentation</a>.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }
        public function max_tokens_callback()
        {
            // $options = get_option($this->option_name);
            $ai_content_x_openai_max_tokens = $this->wp_option($this->option_name)->get('ai_content_x_openai_max_tokens', 100);
            echo $this->ui()->input(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_max_tokens]',
                    'value' => $ai_content_x_openai_max_tokens,
                    'type' => 'number',
                    'min' => 100,
                    'max' => 5000,
                    'required' => true,
                )
            );
            echo $this->ui()->description(
                __('The maximum number of tokens to generate. The maximum is 2048.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }
        public function temperature_callback()
        {
            $ai_content_x_openai_temperature = $this->wp_option($this->option_name)->get('ai_content_x_openai_temperature', 0.9);
            echo $this->ui()->input(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_temperature]',
                    'value' => $ai_content_x_openai_temperature,
                    'type' => 'number',
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'required' => true,
                )
            );
            echo $this->ui()->description(
                __('The higher the temperature, the more random the text. The lower the temperature, the more likely the text will be to make sense. The default is 0.9.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }

        public function top_p_callback()
        {
            $ai_content_x_openai_top_p = $this->wp_option($this->option_name)->get('ai_content_x_openai_top_p', 1);
            echo $this->ui()->input(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_top_p]',
                    'value' => $ai_content_x_openai_top_p,
                    'type' => 'number',
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'required' => true,
                )
            );
            echo $this->ui()->description(
                __('The probability that the model will stop generating text. The default is 1.0.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }
        public function frequency_penalty_callback()
        {
            $ai_content_x_openai_frequency_penalty = $this->wp_option($this->option_name)->get('ai_content_x_openai_frequency_penalty', 0);
            echo $this->ui()->input(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_frequency_penalty]',
                    'value' => $ai_content_x_openai_frequency_penalty,
                    'type' => 'number',
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'required' => true,
                )
            );
            echo $this->ui()->description(
                __('The higher the frequency penalty, the more likely the model will generate new text. The lower the frequency penalty, the more likely the model will repeat text. The default is 0.0.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }
        public function presence_penalty_callback()
        {
            $ai_content_x_openai_presence_penalty = $this->wp_option($this->option_name)->get('ai_content_x_openai_presence_penalty', 0);
            echo $this->ui()->input(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_presence_penalty]',
                    'value' => $ai_content_x_openai_presence_penalty,
                    'type' => 'number',
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1,
                    'required' => true,
                )
            );
            echo $this->ui()->description(
                __('The higher the presence penalty, the more likely the model will generate new text. The lower the presence penalty, the more likely the model will repeat text. The default is 0.0.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }
        public function stop_sequence_callback()
        {
            $ai_content_x_openai_stop_sequence = $this->wp_option($this->option_name)->get('ai_content_x_openai_stop_sequence', '');
            echo $this->ui()->input(
                array(
                    'name' => 'ai_content_x_openai[ai_content_x_openai_stop_sequence]',
                    'value' => $ai_content_x_openai_stop_sequence,
                    'type' => 'text',
                )
            );
            echo $this->ui()->description(
                __('The model will stop generating text when it encounters this sequence. The default is an empty string.', AICX_PRO_PLUGIN_TEXT_DOMAIN)
            );
        }
    }
}
