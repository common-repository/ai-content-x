<?php
/*
 * Plugin Name: AI Content X: GPT-3 Content Generator
 * Plugin URI: https://jodacame.dev/kb/ai-content-x/
 * description: AI Content X is a plugin that allows you to generate content automatically using the OpenAI API. This type of plugin can be used to create new blog posts, articles, or other types of content for a website. AI Content Generator Plugin for WordPress using GPT3 API (OpenAI).
 * Version: 2.0.3
 * Author: Jodacame
 * Author URI: https://jodacame.dev/
 * Support URI: https://jodacame.dev/kb/ai-content-x/
 * Text Domain: ai-content-x
 * Domain Path: /languages
*/


if (!defined('ABSPATH')) exit; /* Exit if accessed directly */

if (defined('AICX_PRO_PLUGIN_VERSION'))
{
    // Exit if the plugin is already defined
    die;
}


/* Define constants */
define('AICX_PRO_PLUGIN_FILE_URL', __FILE__);
define('AICX_PRO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AICX_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AICX_PRO_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('AICX_PRO_PLUGIN_NAME', 'AI Content X');
define('AICX_PRO_PLUGIN_SLUG', 'ai-content-x');
define('AICX_PRO_PLUGIN_VERSION', '2.0.3');
define('AICX_PRO_PLUGIN_TEXT_DOMAIN', 'ai-content-x');
define('AICX_PRO_PLUGIN_POST_TYPE', 'ai_content_x_prompt');
define('AICX_PRO_MODELS', array('text-davinci-003', 'text-curie-001', 'text-babbage-001', 'text-ada-001', 'code-davinci-002', 'code-cushman-001'));


require_once AICX_PRO_PLUGIN_PATH . 'admin/load.php';

AICX_Pro::instance(); // Initialize the plugin
