<?php


/** 
 * Load all admin classes
 * @since 1.5.0
 * @package AI Content X 
 * @category Admin
 */

// Exit if accessed directly
if (!defined('ABSPATH'))
{
    exit;
}

// Load Core Jodacame layer class for WordPress
require_once AICX_PRO_PLUGIN_PATH . 'core/Jodacame.class.php';



require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/Defaults.class.php';
require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/Menus.class.php';
require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/Notice.class.php';
require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/custom-fields/Post.class.php';
require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/Process.class.php';
require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/Ajax.class.php';
require_once AICX_PRO_PLUGIN_PATH . 'admin/inc/Assets.class.php';



if (version_compare(PHP_VERSION, '5.4.0', '<'))
{

    $message      = __('AI Content X plugin requires PHP version 5.4+, plugin is currently NOT ACTIVE. Please contact the hosting provider to upgrade the version of PHP.');
    $html_message = sprintf('<div class="notice notice-error">%s</div>', wpautop($message));
    echo wp_kses_post($html_message);
    return;
}



class AICX_Pro
{


    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance))
        {
            self::$_instance = new self();
            self::$_instance->init();
        }
        return self::$_instance;
    }

    public function init()
    {


        new AICX_Pro_Defaults(); // Load default values
        new AICX_Pro_Menus(); // Load menus
        new AICX_Pro_Notice(); // Load notices
        new AICX_Pro_Custom_Fields_Post(); // Load custom fields for post
        new AICX_Pro_Process(); // Load process
        new AICX_Pro_Ajax(); // Load ajax
        new AICX_Pro_Assets(); // Load assets
    }
}
