<?php
if (!class_exists('AICX_Pro_Process'))
{
    /**
     * Class to handle task scheduling and execution
     * @since 1.6.0
     * @package AI Content X
     * @category Admin
     */

    class AICX_Pro_Process extends Jodacame
    {
        public function __construct()
        {
            $this->hooks();
        }

        /**
         * Register hooks
         * @return void
         */

        public function hooks()
        {
            $this->wp_hook()->add_filter('cron_schedules', array($this, 'register_cron_schedules'));
            $this->wp_hook()->add_action('admin_init', array($this, 'cronjob_set'));
            $this->wp_hook()->add_action('aicx_pro_task', array($this, 'task'));
        }

        /**
         * Register custom cron schedules
         * @param array $schedules
         * @return array
         */

        function register_cron_schedules($schedules)
        {
            $cronjob = $this->wp_option('ai_content_x')->get('cronjob', 0);

            if ($cronjob > 0)
            {
                $schedules['ai_content_x_cron_minutes'] = array(
                    'interval' => $cronjob * 60,
                    'display' => __('Every ' . $cronjob . ' minutes')
                );
            }
            return $schedules;
        }

        /**
         * Set Tas Schedule
         * @return void
         */

        public function cronjob_set()
        {
            if (!isset($_POST))
            {
                return;
            }

            $cronjob = $this->wp_option('ai_content_x')->get('cronjob', 0);

            if ($cronjob > 0)
            {
                if (!$this->wp_scheduled('aicx_pro_task')->next())
                {

                    $this->wp_scheduled('aicx_pro_task')->create(
                        time(),
                        'ai_content_x_cron_minutes',
                    );
                }
            }
            else
            {
                $this->wp_scheduled('aicx_pro_task')->delete();
            }
        }


        public function task()
        {

            $cronjob_number_post = $this->wp_option('ai_content_x')->get('cronjob_number_post', 1);
            $extract_title   = $this->wp_option('ai_content_x')->get('extract_title', 0);
            $cronjob_number_post = $cronjob_number_post > 0 ? $cronjob_number_post : 1;
            $cronjob_post_status = $this->wp_option('ai_content_x')->get('cronjob_post_status', 'draft');
            $new_status_post = $this->wp_option('ai_content_x')->get('new_status_post', 'publish');
            $cronjob_post_type = $this->wp_option('ai_content_x')->get('cronjob_post_type', 'post');

            $args = array(
                'post_type' => $cronjob_post_type,
                'post_status' => $cronjob_post_status ?? 'draft',
                'meta_key' => 'ai_content_x_queue',
                'meta_value' => 'on',
                'orderby' => 'ID',
                'order' => 'ASC',
                'numberposts' => intval($cronjob_number_post)
            );


            if ($_POST['post_id'])
            {
                $args['p'] = $_POST['post_id'];
            }

            $posts = $this->wp_post()->get($args);

            $email_notification = $this->wp_option('ai_content_x')->get('email_notification', '');

            if (count($posts) > 0)
            {
                //while ($query->have_posts())
                foreach ($posts as $post)
                {
                    // $query->the_post();
                    $post_id = $post->ID;
                    $post->post_status = $new_status_post;
                    // update post content  
                    $post_title = $post->post_title;
                    $post_content =  ($post->post_content);
                    $post->post_content = $this->generate_content($post_id, $post_title, $post_content);
                    if ($post->post_content)
                    {

                        if ($extract_title)
                        {
                            // extract h1 tag from content using regex 
                            preg_match('/<h1>(.*?)<\/h1>/', $post->post_content, $matches);
                            // remoce h1 tag from content
                            $post->post_content = preg_replace('/<h1>(.*?)<\/h1>/', '', $post->post_content);
                            if (isset($matches[1]))
                            {
                                $post->post_title = $matches[1];
                            }
                        }
                        else
                        {
                            $post->post_content = preg_replace('/<h1>(.*?)<\/h1>/', '<h2>$1</h2>', $post->post_content);
                        }


                        $post->post_content  = $this->fixed_content($post->post_content);

                        $this->wp_post($post_id)->update($post);

                        $this->wp_post($post_id)->setMeta('ai_content_x_queue', 0);

                        $this->wp_post($post_id)->setMeta('ai_content_x_queue_date', date('Y-m-d H:i:s'));
                    }
                }
            }
            //wp_reset_postdata();
        }

        /**
         * Generate content
         * @param int $post_id Post ID
         * @param string $post_title Post title
         * @param string $post_content Post content
         * @return string
         */

        public function generate_content($post_id, $post_title, $post_content)
        {


            $openai_options = $this->wp_option('ai_content_x_openai')->get();
            $prompt = $this->wp_option('ai_content_x')->get('prompt', '');

            $custom_prompt_id = $this->wp_post($post_id)->getMeta('ai_content_x_queue_prompt', true);
            if ($custom_prompt_id)
            {
                $custom_prompt = $this->wp_post($custom_prompt_id)->get();
                if ($custom_prompt)
                {
                    $custom_meta_prompt = $this->wp_post($custom_prompt->ID)->getMeta('_ai_content_x_prompt', true);
                    $use_custom_openai = $this->wp_post($custom_prompt->ID)->getMeta('custom_openai', true);
                    if ($custom_meta_prompt)
                    {
                        $prompt = $custom_meta_prompt;

                        if ($use_custom_openai)
                        {
                            $openai__custom_options = $this->wp_post($custom_prompt->ID)->getMeta('openai', true);
                            $openai_options = array_merge($openai_options, $openai__custom_options);
                        }
                    }
                }
            }

            $prompt = str_replace('%post_title%', $post_title, $prompt);
            $prompt = str_replace('%post_content%', $post_content, $prompt);

            // remove all html tags
            $prompt = strip_tags($prompt);


            $openai_api_key = $openai_options['ai_content_x_openai_api_key'] ?? '';
            $openai_model = $openai_options['ai_content_x_openai_model'] ?? '';
            $openai_max_tokens = $openai_options['ai_content_x_openai_max_tokens'] ?? '';
            $openai_temperature = $openai_options['ai_content_x_openai_temperature'] ?? '';
            $openai_top_p = $openai_options['ai_content_x_openai_top_p'] ?? '';
            $openai_frequency_penalty = $openai_options['ai_content_x_openai_frequency_penalty'] ?? '';
            $openai_presence_penalty = $openai_options['ai_content_x_openai_presence_penalty'] ?? '';
            $openai_stop = $openai_options['ai_content_x_openai_stop'] ?? '';

            $openai_setting_text = '';
            foreach ($openai_options as $key => $value)
            {
                if ($key != 'ai_content_x_openai_api_key')
                {
                    $openai_setting_text .=  str_ireplace('ai_content_x_openai_', '', $key)  . ': ' . $value . ', ';
                }
            }
            $openai_setting_text = rtrim($openai_setting_text, ', ');
            $openai_setting_text = 'OpenAI Settings: ' . $openai_setting_text;



            if (!$openai_api_key || !$openai_model || !$prompt)
            {
                $this->save_log('error', $post_id, $prompt, 'Error: Missing required fields');
                return false;
            }
            $data = array(
                'prompt' => $prompt,
                'model' => $openai_model,
                'max_tokens' => intval($openai_max_tokens),
                'temperature' => floatval($openai_temperature),
                'top_p' => floatval($openai_top_p),
                'frequency_penalty' => floatval($openai_frequency_penalty),
                'presence_penalty' => floatval($openai_presence_penalty),
                'stop' => $openai_stop,
            );


            // Set headers


            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $openai_api_key,
            );

            $args = array(
                'method' => 'POST',
                'body'        => json_encode($data),
                'timeout'     => '320',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => $headers,
                'cookies'     => array(),
            );


            $request = $this->wp_request('https://api.openai.com/v1/completions')->post($args);


            if ($request['status'] === 'error')
            {
                $this->save_log('error', $post_id, $prompt, $request->status . ': ' . $request['message']);
                return false;
            }

            $result = $request['data'];


            $result = json_decode($result, true);



            if (!$result)
            {
                $errorString = 'Error (No Result)';
                $this->save_log('error', $post_id, $prompt, $errorString);
                return false;
            }
            if ($result['error'])
            {
                $this->save_log('error', $post_id, $prompt, 'Error: ' . $result['error']['message']);
                return false;
            }

            $text = $result['choices'][0]['text'];
            if (!$text)
            {
                $this->save_log('error', $post_id, $prompt, 'Error: No text generated');
                return false;
            }

            $this->save_log('success', $post_id, $prompt, $openai_setting_text);
            return $text;
        }

        /**
         * Save log
         *
         * @param string $type (success, error)
         * @param int $post_id 
         * @param string $promp 
         * @param string $message
         * @return void
         */

        public function save_log($type, $post_id, $promp, $message)
        {

            $this->wp_db()->insert(
                'ai_content_x_logs',
                array(
                    'type' => $type,
                    'post_id' => $post_id,
                    'prompt' => $promp,
                    'message' => $message,
                    'created_at' =>  date('Y-m-d H:i:s'),
                )
            );
        }

        /**
         * Fix content (broken sentences)
         * @param string $content 
         * @return string
         */

        function fixed_content($content)
        {

            $last_char = substr($content, -1);
            if ($last_char === '.')
            {
                return $content;
            }
            $pg = explode(".", $content);
            array_pop($pg);
            $content = implode(".", $pg);
            return $content . ".";
        }
    }
}
