<?php
/*
Plugin Name: Old Quotes Promoter - OPP
Plugin URI: http://www.blogtrafficexchange.com/old-quote-promoter/
Author: Blog Traffic Exchange
Author URI: http://www.blogtrafficexchange.com
Version: 3.0.1
Description: Wordpress plugin that helps you to promote older quotes by moving them back onto the front page and into the rss feed. WARNING: This plugin should only be used with data agnostic permalinks (permalink structures not containing dates).
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Old Quotes Promoter is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.
 
Old Quotes Promoter is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See th GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License along with Old Quotes Promoter. If not, see {License URI}.
*/
define("MMPLUGINPATH", realpath(dirname(__FILE__) ));
define("MMPLUGINURL", plugin_dir_url(__FILE__));
define("MMICONURL",MMPLUGINURL."images/icon.png");
define("MMCSS",MMPLUGINURL."css/old_quote_promoter.css");

define ('MMMINUTE1', 60); 
define ('MMMINUTE15', 15*MMMINUTE1); 
define ('MMMINUTE30', 30*MMMINUTE1); 
define ('MMHOUR1', 60*MMMINUTE1); 
define ('MMHOUR4', 4*MMHOUR1); 
define ('MMHOUR6', 6*MMHOUR1); 
define ('MMHOUR12', 12*MMHOUR1); 
define ('MMHOUR24', 24*MMHOUR1); 
define ('MMHOUR48', 48*MMHOUR1); 
define ('MMHOUR72', 72*MMHOUR1); 
define ('MMHOUR168', 168*MMHOUR1); 
define ('MMINTERVAL', MMHOUR12); 
define ('MMINTERVALSLOP', MMHOUR4); 
define ('MMAGELIMIT', 120); // 120 days
define ('MMOMITCATS', ""); 

require_once(MMPLUGINPATH."/inc/mm-opp-register-hook.php");
require_once(MMPLUGINPATH."/inc/mm-opp-core.php");
require_once(MMPLUGINPATH."/admin/mm-admin.php");

register_activation_hook(__FILE__, 'mm_opp_activate');
register_deactivation_hook(__FILE__, 'mm_opp_deactivate');

add_action('init', 'mm_opp_old_quote_promoter');
add_action('admin_menu', 'mm_opp_menu_setup');
add_action('admin_head', 'mm_opp_admin_head');
add_filter('the_content', 'mm_opp_the_content');
add_filter('plugin_action_links', 'mm_opp_action_links', 10, 2);

function mm_opp_action_links($links, $file) {
	$plugin_file = basename(__FILE__);
	if (basename($file) == $plugin_file) {
		$settings_link = '<a href="admin.php?page=old-quote-promoter">'.__('Configure', 'RelatedTweets').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
} ?>
