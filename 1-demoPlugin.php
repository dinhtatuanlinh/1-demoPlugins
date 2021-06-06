<?php
/**
 * @package 1-demoPlugins
 * @version 1.7.2
 */
/*
Plugin Name: 1-demoPlugins
Plugin URI: http://noteatext.com
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: dttl
Version: 1.00
Author URI: http://noteatext.com
*/
if (is_admin()){
    require_once dirname(__FILE__) . '/inc/admin.php'; // dirname(__FILE__) gọi ra đường dẫn chứa file chạy chính
} else{
    require_once dirname(__FILE__) . '/inc/public.php'; // dirname(__FILE__) gọi ra đường dẫn chứa file chạy chính
}
?>