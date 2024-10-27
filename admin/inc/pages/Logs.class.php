<?php

if (!class_exists('AICX_Logs_Page'))
{
    /**
     * Class to display logs page
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Page_Logs extends Jodacame
    {

        public function __construct()
        {
        }

        /**
         * Display logs page
         * @return void
         */

        public function page()
        {
            if (!$this->wp_user('me')->can('manage_options'))
            {
                return;
            }





            $logs = $this->wp_db()->get(
                array(
                    'table' =>  'ai_content_x_logs',
                    'limit' => 10,
                    'order' => 'id DESC',
                )
            );


            $cronjob_post_status = $this->wp_option('ai_content_x')->get('cronjob_post_status') ?? 'draft';
            $cronjob_post_type = $this->wp_option('ai_content_x')->get('cronjob_post_type');

            $args = array(
                'post_type' => $cronjob_post_type,
                'post_status' => $cronjob_post_status ?? 'draft',
                'meta_key' => 'ai_content_x_queue',
                'meta_value' => 'on',
                'orderby' => 'ID',
                'order' => 'ASC',
                'numberposts' => 1,
            );
            $post = $this->wp_post()->getOne($args);

?>

            <div class="wrap ai-content-x-page">
                <div class="nav-tab-wrapper ai-content-tabs">
                    <img src="<?php echo AICX_PRO_PLUGIN_URL; ?>assets/img/icon-color-64.png" alt="AI Content X" />
                    <div class="title">
                        <?php echo __('Queue & Logs', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                    </div>
                    <div class="ai-content-is-spacer"></div>

                    <?php
                    echo $this->ui()->link(
                        __('Upgrade Pro', AICX_PRO_PLUGIN_TEXT_DOMAIN),
                        'https://jodacame.dev/ai-content-x/free-vs-pro/',
                        array(
                            'class' => 'ai-content-button ai-content-primary',
                            'target' => '_blank',
                        )
                    );


                    if ($post)
                    {
                    ?>
                        <button class='ai-content-button ai-content-primary' data-text="<?php echo  __('Run Now', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>" data-post-id="<?php echo $post->ID; ?>" onclick="aicxpro.queue.run(this,true)">
                            <?php echo  __('Run Now', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                        </button>
                    <?php } ?>

                </div>


                <table class="ai-content-x-table">
                    <thead>
                        <tr>
                            <th>
                                <div>
                                    <img src="<?php echo AICX_PRO_PLUGIN_URL; ?>assets/img/icon-color-64.png" alt="AI Content X" />
                                    <?php echo __('Next Post', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>

                                </div>
                            </th>
                            <th><?php echo __('Next Run', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$post)
                        {
                        ?>
                            <tr>
                                <td colspan="2">
                                    <?php
                                    echo __('No post in queue', AICX_PRO_PLUGIN_TEXT_DOMAIN);
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                        else
                        {

                            $openai_settings_text = __('Default OpenAI Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN);
                            $prompt = $this->wp_option('ai_content_x')->get('prompt');

                            $custom_prompt_id = $this->wp_post($post->ID)->getMeta('ai_content_x_queue_prompt', true);



                            if ($custom_prompt_id)
                            {

                                $custom_prompt = $this->wp_post($custom_prompt_id)->get();

                                if ($custom_prompt)
                                {

                                    $prompt = $this->wp_post($custom_prompt->ID)->getMeta('_ai_content_x_prompt', true);
                                    // $openai_settings = $this->wp_post($custom_prompt->ID)->getMeta('openai', true);
                                    $custom_openai = $this->wp_post($custom_prompt->ID)->getMeta('custom_openai', true);
                                    if ($custom_openai)
                                    {
                                        $openai_settings_text =
                                            "<a href='" . get_edit_post_link($custom_prompt->ID) . "'>"
                                            . __('Custom OpenAI Settings', AICX_PRO_PLUGIN_TEXT_DOMAIN) .
                                            " (" . $custom_prompt->post_title . ")"
                                            . "</a>";
                                    }
                                }
                            }



                            $prompt = str_replace('%post_title%', '<strong>' . $post->post_title . '</strong>', $prompt);
                            $prompt = str_replace('%post_content%', '<strong>' . $post->post_content . '</strong>', $prompt);

                            $prompt = strip_tags($prompt);


                            // truncate to 256 characters
                            if (strlen($prompt) > 512)
                            {
                                $prompt = substr($prompt, 0, 512) . '...';
                            }
                            $image = $this->wp_post($post->ID)->thumbnail();
                            if (!$image)
                            {
                                $image =  AICX_PRO_PLUGIN_URL . "assets/img/no-image.png";
                            }
                        ?>
                            <tr>
                                <td>
                                    <div class="ai-content-is-flex ai-content-is-gap-10">
                                        <img src='<?php echo $image; ?>' class="ai-content-x-picture-log">
                                        <div>
                                            <a href='<?php echo $this->wp_post($post->ID)->link('edit'); ?>'>
                                                <h3 style='margin:0;padding:0;padding-bottom:5px'>
                                                    <?php echo $post->post_title;  ?>
                                                </h3>
                                            </a>
                                            <?php echo esc_html($prompt) ?>
                                            <div>
                                                <p class="description hightlight">
                                                    <span class="dashicons dashicons-admin-generic"></span>
                                                    <?php echo $openai_settings_text; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style='text-align:center'>
                                    <strong>
                                        <?php echo $this->ui()->human_time_diff($this->wp_scheduled('aicx_pro_task')->next(), current_time('timestamp')); ?>
                                    </strong>

                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                    <tfood>
                        <tr>
                            <td colspan="2" class="right">

                                <a href="<?php echo get_admin_url(); ?>edit.php?post_status=<?php echo $cronjob_post_status; ?>&post_type=<?php echo $cronjob_post_type; ?>&ai_content_x_queue=on">

                                    <span class="dashicons dashicons-editor-ul"></span>
                                    <?php echo __('View All Post in Queue', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>

                                </a>


                            </td>
                        </tr>
                    </tfood>
                </table>


                <?php
                if (count($logs) > 0)
                {

                ?>
                    <div id="ai-content-x-logs-container">
                        <h2>
                            <?php echo __('Lastest 10 logs', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                        </h2>
                        <table class="ai-content-x-table">
                            <thead>
                                <tr>
                                    <th style="width:60%">Post</th>
                                    <th>Prompt</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($logs as $log)
                                {
                                    $post = get_post($log->post_id);
                                    if (!$post)
                                    {
                                        continue;
                                    }
                                    $excerpt = $post->post_content;
                                    $excerpt  = strip_tags($excerpt);
                                    if (strlen($excerpt) > 100)
                                    {
                                        $excerpt = substr($excerpt, 0, 200) . '...';
                                    }

                                    $image = $this->wp_post($post->ID)->thumbnail();
                                    if (!$image)
                                    {
                                        $image =  AICX_PRO_PLUGIN_URL . "assets/img/no-image.png";
                                    }

                                ?>
                                    <tr>
                                        <td>
                                            <div class="ai-content-is-flex ai-content-is-gap-10">
                                                <a href='<?php echo get_edit_post_link($log->post_id); ?>'>
                                                    <img src='<?php echo $image; ?>' class="ai-content-x-picture-log">
                                                </a>
                                                <div>
                                                    <a href='<?php echo get_edit_post_link($log->post_id); ?>'>
                                                        <strong><?php echo $post->post_title; ?></strong>
                                                    </a>
                                                    <p>
                                                        <?php echo $excerpt; ?>
                                                    </p>
                                                    <small>
                                                        <?php echo $post->post_status; ?> |
                                                        <?php echo human_time_diff(strtotime($log->created_at), current_time('timestamp')) . __(" ago", AICX_PRO_PLUGIN_TEXT_DOMAIN); ?></small>
                                                </div>
                                            </div>

                                        </td>
                                        <td>
                                            <div id="prompt-<?php echo $log->id; ?>" style="display:none">
                                                <?php echo esc_html($log->prompt); ?>
                                            </div>
                                            <div id="prompt-truncate-<?php echo $log->id; ?>">

                                                <?php
                                                $prompt = esc_html($log->prompt);
                                                $show_more = false;
                                                if (strlen($prompt) > 256)
                                                {
                                                    $show_more = true;
                                                    $prompt = substr($prompt, 0, 256) . '...';
                                                }


                                                echo "<p class='description'>" . $prompt . "</p>";
                                                if ($show_more)
                                                {
                                                ?>
                                                    <a href="#" onclick="aicxpro.elm.show('#prompt-<?php echo $log->id; ?>');aicxpro.elm.hide('#prompt-truncate-<?php echo $log->id; ?>');return false;">
                                                        <?php echo __('Show more', AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                                                    </a>
                                                <?php } ?>
                                            </div>


                                            <div>
                                                <span class='ai-content-x-label cursor-pointer <?php echo $log->type; ?>' onclick="aicxpro.elm.toggle('#aicx-debug-message-<?php echo $log->id; ?>');">
                                                    <?php echo $log->type; ?>
                                                </span>



                                                <p class=" description hightlight" id="aicx-debug-message-<?php echo $log->id; ?>" style='max-width:400px;display:none'>
                                                    <?php echo $log->message; ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>
            </div>
            <a href="https://jodacame.dev/kb/ai-content-x/" target="_blank" class="ai-content-x-pro-doc-float">
                <img src="<?php echo AICX_PRO_PLUGIN_URL; ?>assets/img/icon-color-64.png" alt="Documentation" />
                <?php echo __("Documentation", AICX_PRO_PLUGIN_TEXT_DOMAIN); ?>
                <small style="margin-left:5px"><?php echo __("Ver.", AICX_PRO_PLUGIN_TEXT_DOMAIN); ?> <?php echo AICX_PRO_PLUGIN_VERSION; ?> </small>
            </a>
<?php
        }
    }
}
