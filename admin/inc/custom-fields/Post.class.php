<?php


if (!class_exists('AICX_Pro_Custom_Fields_Post'))
{
    /**
     * Class to register custom fields for post
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Custom_Fields_Post extends Jodacame
    {

        public function __construct()
        {
            $this->hooks();
        }

        public function hooks()
        {



            /** Register into WordPress */
            $this->wp_hook()->add_action('save_post', array($this, 'save'));
            $this->wp_hook()->add_action('add_meta_boxes', array($this, 'add_metabox'));
            $this->wp_hook()->add_action('manage_posts_custom_column', array($this, 'add_column_content'), 10, 2);
            $this->wp_hook()->add_filter('manage_posts_columns', array($this, 'add_column'));
        }
        public function add_metabox()
        {


            $cronjob_post_type = $this->wp_option('ai_content_x')->get('cronjob_post_type');

            $screens = [$cronjob_post_type];
            foreach ($screens as $screen)
            {
                $this->wp_metabox()->create(
                    'ai_content_x_sectionid',
                    __('AI Content X', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                    array($this, 'options'),
                    $screen,
                    'side',
                    'high'
                );
            }
        }


        public function options($post)
        {
            if (!$this->wp_user('me')->can('edit_post', $post->ID))
            {
                return;
            }


            // Add a nonce field so we can check for it later.
            $this->wp_nonce()->field('ai_content_x_queue_box', 'ai_content_x_queue_box_nonce');


            $value = $this->wp_post($post->ID)->getMeta('ai_content_x_queue', true);
            $value_date = $this->wp_post($post->ID)->getMeta('ai_content_x_queue_date', true);
            $checked = $value === 'on' ? 'checked' : '';

?>
            <div class="ai-content-x-boxed">
                <img src="<?php echo AICX_PRO_PLUGIN_URL; ?>assets/img/generator.png" alt="AI Content X" class="ai-content-x-image-title" />
                <p class='description'>
                    <?php __("This post will be automatically generated using artificial intelligence in background process.", AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                </p>
                <div class="ai-content-x-checkbox">
                    <input type="checkbox" id="ai_content_x_queue" name="ai_content_x_queue" <?php echo  $checked; ?> />
                    <label for="ai_content_x_queue">
                        <?php echo __('Generate content automatically', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                    </label>

                </div>
                <?php
                if ($value_date)
                {
                ?>
                    <p class='description'>
                        <?php echo __("Last generation: ", AICX_PRO_PLUGIN_TEXT_DOMAIN) . $this->ui()->human_time_diff(strtotime($value_date), current_time('timestamp')) . " ago"; ?>
                    </p>
                <?php
                }
                ?>
            </div>
            <?php
        }


        public function add_column($defaults)
        {
            // check if post type is post 
            //$post_type = get_post_type();
            global $post_type;

            $cronjob_post_type = $this->wp_option('ai_content_x')->get('cronjob_post_type');

            if ($post_type !== $cronjob_post_type)
            {

                return $defaults;
            }
            $defaults['ai_content_x_queue'] = __('AI Content X', AICX_PRO_PLUGIN_TEXT_DOMAIN);



            return $defaults;
        }


        /** Function to add column content to post list */

        public function add_column_content($column_name, $post_ID)
        {


            global $post_type;

            $cronjob_post_type =  $this->wp_option('ai_content_x')->get('cronjob_post_type');

            if ($post_type !== $cronjob_post_type)
            {

                return;
            }

            if ($column_name === 'ai_content_x_queue')
            {


                $value = $this->wp_post($post_ID)->getMeta('ai_content_x_queue', true);
                $value_date = $this->wp_post($post_ID)->getMeta('ai_content_x_queue_date', true);
                $checked = $value === 'on' ? 'checked' : '';
                $post_status = $this->wp_post($post_ID)->get()->post_status;
                $cront_job_post_status = $this->wp_option('ai_content_x')->get('cronjob_post_status');
                $all_status_post = $this->wp_post()->statuses();

                if ($post_status !== $cront_job_post_status && $value === 'on')
                {
            ?>
                    <div class="ai-content-x-error">
                        <small>
                            <?php
                            printf(
                                __("Post status is <strong>%s</strong>. Change it to <strong>%s</strong> to add it to the queue or uncheck the option 'Generate content automatically'", AICX_PRO_PLUGIN_TEXT_DOMAIN),
                                $all_status_post[$post_status],
                                $all_status_post[$cront_job_post_status]
                            );
                            ?>
                        </small>
                    </div>
                <?php
                    $value = 'off';
                }


                if ($value_date)
                {
                ?>
                    <div>
                        <?php echo __("Generated", AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                        <br>
                        <?php echo $this->ui()->date($value_date); ?>
                    </div>
                    <?php
                }
                if ($value === 'on')
                {

                    $prompt_id = $this->wp_post($post_ID)->getMeta('ai_content_x_queue_prompt', true);

                    if ($prompt_id)
                    {
                        $prompt = $this->wp_post($prompt_id)->get();
                        if ($prompt)
                        {
                    ?>
                            <img src='<?php echo AICX_PRO_PLUGIN_URL; ?>assets/img/icon-color-64.png'>

                            <?php echo $prompt->post_title; ?>

                        <?php
                        }
                        else
                        {
                        ?>
                            <img src='<?php echo AICX_PRO_PLUGIN_URL; ?>assets/img/icon-color-64.png'>
                            <span>Default</span>
                        <?php
                        }
                    }
                    else
                    {
                        ?>
                        <img src='<?php echo AICX_PRO_PLUGIN_URL; ?>assets/img/icon-color-64.png'>
                        <span>Default</span>
                    <?php
                    }
                    ?>
                    <br>

                    <?php
                    if ($value === 'on')
                    {
                    ?>
                        <input type='checkbox' disabled <?php echo $checked; ?> />
                        <a href='<?php echo admin_url('admin.php?page=ai-content-x-logs'); ?>'>
                            <?php echo  __('Queued', AICX_PRO_PLUGIN_TEXT_DOMAIN);  ?>
                        </a>
                        <button type="button" class='ai-content-button ai-content-primary ai-content-button-small ai-content-x-run-now' data-text='<?php echo __('Run now', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>' onclick="aicxpro.alert('Available only in PRO Version','error')">
                            <?php echo __('Run now', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                        </button>
<?php
                    }
                    else
                    {
                        echo __('Not Queued', AICX_PRO_PLUGIN_TEXT_DOMAIN);
                    }
                }
            }
        }








        /** Function to save custom fields */

        public function save($post_id)
        {
            // Only save custom fields if the user has the right permissions
            if (!$this->wp_user('me')->can('edit_post', $post_id))
            {
                return;
            }

            // Check if our nonce is set.
            if (!isset($_POST['ai_content_x_queue_box_nonce']))
            {
                return;
            }

            // Verify that the nonce is valid.
            if (!$this->wp_nonce()->verify($_POST['ai_content_x_queue_box_nonce'], 'ai_content_x_queue_box'))
            {
                return;
            }

            // If this is an autosave, our form has not been submitted,
            // so we don't want to do anything.
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }

            // Check the user's permissions.
            if (isset($_POST['post_type']) && 'page' === $_POST['post_type'])
            {
                if (!$this->wp_user('me')->can('edit_page', $post_id))
                {
                    return;
                }
            }
            else
            {
                if (!$this->wp_user('me')->can('edit_post', $post_id))
                {
                    return;
                }
            }
            // Sanitize user input.
            $add_queue = $this->wp_option()->sanitize_text_field($_POST['ai_content_x_queue']);
            $prompt_selected = $this->wp_option()->sanitize_text_field($_POST['ai_content_x_queue_prompt']);

            // Update the meta field in the database.
            $this->wp_post($post_id)->setMeta('ai_content_x_queue', $add_queue);
            $this->wp_post($post_id)->setMeta('ai_content_x_queue_prompt', $prompt_selected);
        }
    }
}
