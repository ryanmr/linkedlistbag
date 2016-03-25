<?php
/**
 * Plugin Name:			Linked List Bag
 * Description:			Adds Linked List functionality, supporting source via links, and feed enhancements.
 * Version:				0.1.0
 * Author:				Ryan Rampersad
 * Author URI:			http://ryanrampersad.com
 */

if (!defined('ABSPATH')) {
	exit();
}

define('LL_CORE', __FILE__);
define('LL_CORE_PATH', plugin_dir_path( __FILE__ ));
define('LL_CORE_URL', plugin_dir_url( __FILE__ ));
define('LL_CORE_VIEWS', LL_CORE_PATH . 'views/');

require_once('linkedlistbag/Singleton.php');
require_once('linkedlistbag/Core.php');
require_once('linkedlistbag/Metaboxes.php');
require_once('linkedlistbag/Meta.php');
require_once('linkedlistbag/Feeds.php');
require_once('linkedlistbag/metaboxes/AbstractMetabox.php');
require_once('linkedlistbag/metaboxes/LinkedListMetabox.php');


\LinkedList\Core::get_instance()->initialize();
\LinkedList\Metaboxes::get_instance()->initialize();
\LinkedList\Meta::get_instance()->initialize();
\LinkedList\Feeds::get_instance()->initialize();
