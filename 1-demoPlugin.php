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
// định nghĩa các hằng số đường dẫn
define('DTTL_PL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DTTL_PL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DTTL_PL_VIEWS_DIR', DTTL_PL_PLUGIN_DIR . '/views');
define('DTTL_PL_INC_DIR', DTTL_PL_PLUGIN_DIR . '/inc');
define('DTTL_PL_WIDGET_DIR', DTTL_PL_PLUGIN_DIR . '/widgets');
define('DTTL_PL_ASSETS_DIR', DTTL_PL_PLUGIN_URL . 'assets/');
define('DTTL_PL_CSS_DIR', DTTL_PL_ASSETS_DIR . 'css/');
define('DTTL_PL_JS_DIR', DTTL_PL_ASSETS_DIR . 'js/');

if(!is_admin()){
    
    require_once DTTL_PL_PLUGIN_DIR . 'public.php';
    new dttl_pl_pub();


}else{
    require_once DTTL_PL_INC_DIR . '/html.php';
    // file admin.php là file để tạo ra 1 menu ví dụ trong phần dashboard quản trị
    require_once DTTL_PL_PLUGIN_DIR . 'admin.php';
    new dttl_pl_ad();
    // kéo lớp tạo dashboard widget vào
    require_once DTTL_PL_WIDGET_DIR . '/dashboard_widget.php';
    new dttl_dashboard_widget;

}

// tạo shortcode
// hàm tạo shortcode phải nằm ở cả vùng admin và ngoài admin cụ thể ở vị trí này
require_once DTTL_PL_INC_DIR . '/dttl_shortcode.php';
new dttl_shortcode;
// kéo file a_widget_example.php
require_once DTTL_PL_WIDGET_DIR . '/a_widget_example.php';
// gắn hàm tạo widget ở dưới vào action hook widgets_init
// sau khi đăng ký 1 widget sẽ được tạo trong phần widget của dashboard widget sẽ được tạo khi chỉ cần viết mỗi hàm construct
add_action('widgets_init', 'register_a_widget');
// tạo hàm đăng ký widget
function register_a_widget (){
    // hàm register_widget dùng để đăng ký widget với tham số truyền vào là tên class tạo widget mà được kéo vào ở trên
    register_widget('dttl_pl_a_widget_example');
}
// loại bỏ widget đã tồn tại bằng phương thức
add_action('widgets_init', 'unregister_a_widget');
function unregister_a_widget(){
    unregister_widget('dttl_pl_a_widget_example');
}
// lấy đường dẫn của plugin hiện tại
$cssUrl = plugins_url('/css/abc.css', __FILE__); // hamf plugins_url lấy đường dẫn tới plugin hiện tại và nối với đường dẫn đưa vào
// thực hiện các lệnh khi active plugin
register_activation_hook(__FILE__, 'dttl_pl_active');// chạy các hàm khi active plugin
// tạo bảng để lưu dữ liệu khi kích hoạt plugin
global $wpdb; // đối tượng để tương tác với database;
// thao tác với database
require_once DTTL_PL_INC_DIR . '/database_manipulation.php';
$dttl_database_manipulation = new dttl_database_manipulation;
$result = $dttl_database_manipulation->get_data($wpdb,'options', 'autoload', 'yes');

function dttl_pl_active(){
    global $wpdb;
    // đưa 1 mảng vào bảng wp-option
    $dttl_pl_options = array(
        'course' => 'wordpress',
        'author'=> 'dttl'
    );
    // option api
    // tham so thu nhat là tên option name, tham số thứ 2 là giá trị của row này, tham số thứ 4 là yes để tự động chạy khi kích hoạt plugin
    add_option('dttl_pl_option', $dttl_pl_options, '', 'yes');
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
    // kiểm tra trong database đã tồn tại bảng hay chưa nếu có sẽ trả về tên bảng đó
    if ($wpdb->get_var("SHOW TABLES LIKE `" . $table_name . "`") != $table_name){
        $sql = "CREATE TABLE `" . $table_name ."` (
            `myid` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
            `my_name` varchar(50) DEFAULT NULL,
            PRIMARY KEY (`myid`))
            ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
            ";
            // gọi các phương thức có trong file upgrade.php
        require_once ABSPATH . '/wp-admin/includes/upgrade.php';
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
?>