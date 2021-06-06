<?php
/**
 * @package 1-demoPlugins
 * @version 1.7.2
 */
/*
Plugin Name: 1-demoPlugins
Plugin URI: http://noteatext.com
Description: This is my first plugin for demo
Author: dttl
Version: 1.00
Author URI: http://noteatext.com
*/
// thực hiện các lệnh khi active plugin
register_activation_hook(__FILE__, 'dttl_pl_active');// chạy các hàm khi active plugin
// tạo bảng để lưu dữ liệu khi kích hoạt plugin
global $wpdb; // đối tượng để tương tác với database;

function dttl_pl_active(){
    global $wpdb;
    // đưa 1 mảng vào bảng wp-option
    $dttl_pl_options = array(
        'course' => 'wordpress',
        'author'=> 'dttl'
    );
    // option api
    add_option('dttl_pl_option', $dttl_pl_options, '', 'yes');// tham so thu nhat là tên option name, tham số thứ 2 là giá trị của row này, tham số thứ 4 là yes để tự động chạy khi kích hoạt plugin
    $table_name = $wpdb->prefix . 'options';
    $wpdb->update(
        $table_name,
        array('autoload' => 'yes'),
        array('option_name' => 'dttl_pl_option'),
        array('%s'),// khai báo cho giá trị thứ nhất (array('autoload' => 'no')) đưa vào bảng là string
        array('%s')// khai báo cho giá trị thứ hai (array('option_name' => 'dttl_pl_option')) đưa vào bảng là string
        // nếu là number thì là %n
    );
    // chú ý option name không được trùng với option name đã có
    // mảng được chuyển sang chuỗi được lưu dưới dạng chuỗi bằng phương thức serialize($arr) để chuyển lại thành mảng dùng unserialize($str)
    $dttl_pl_version = '1.00';
    // option api
    add_option('dttl_pl_version', $dttl_pl_version, '', 'yes');// tham so thu nhat là tên option name, tham số thứ 2 là giá trị của row này, tham số thứ 4 là yes để tự động chạy khi kích hoạt plugin
    // ############################################
    
    $table_name = $wpdb->prefix . 'dttl_pl_test';

    if ($wpdb->get_var("SHOW TABLES LIKE `" . $table_name . "`") != $table_name){// kiểm tra trong database đã tồn tại bảng hay chưa nếu có sẽ trả về tên bảng đó
        $sql = "CREATE TABLE `" . $table_name ."` (
            `myid` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
            `my_name` varchar(50) DEFAULT NULL,
            PRIMARY KEY (`myid`))
            ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
            ";
        require_once ABSPATH . '/wp-admin/includes/upgrade.php';// gọi các phương thức có trong file upgrade.php
        dbDelta($sql);// hàm thực thi câu lệnh sql
    }
}   
// function dttl_pl_active(){
//     $dttl_pl_version = '1.00';
//     // option api
//     add_option('dttl_pl_version', $dttl_pl_version, '', 'yes');// tham so thu nhat là tên option name, tham số thứ 2 là giá trị của row này, tham số thứ 4 là yes để tự động chạy khi kích hoạt plugin

// }
// ################################################
// thực thi các lệnh khi deactive plugins
// dừng autoload các thông số trong bảng wp_option
register_deactivation_hook(__FILE__, 'dttl_pl_deactive');// chạy các hàm khi deactive
function dttl_pl_deactive(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'options';
    $wpdb->update(
        $table_name,
        array('autoload' => 'no'),
        array('option_name' => 'dttl_pl_option'),
        array('%s'),// khai báo cho giá trị thứ nhất (array('autoload' => 'no')) đưa vào bảng là string
        array('%s')// khai báo cho giá trị thứ hai (array('option_name' => 'dttl_pl_option')) đưa vào bảng là string
        // nếu là number thì là %n
    );
}

register_uninstall_hook(__FILE__, 'dttl_pl_uninstall');
function dttl_pl_uninstall(){
    global $wpdb;
    delete_option('dttl_pl_version');
    delete_option('dttl_pl_option');
    $table_name = $wpdb->prefix . 'dttl_pl_test';
    $sql = "DROP TABLE IF EXISTS " . $table_name;
    $wpdb->query($sql);// thực thi sql bằng đối tượng wpdb
}
$path = dirname(__FILE__) . '/inc/admin.php';
if (is_admin()){
    require_once dirname(__FILE__) . '/inc/admin.php'; // dirname(__FILE__) gọi ra đường dẫn chứa file chạy chính
} else{
    require_once dirname(__FILE__) . '/inc/public.php'; // dirname(__FILE__) gọi ra đường dẫn chứa file chạy chính
}
?>