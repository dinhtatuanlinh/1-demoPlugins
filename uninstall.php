<?php
if(!defined('WP_UNINSTALL_PLUGIN')){// kiểm tra sự tồn tại của hằng WP_UNINSTALL_PLUGINS nếu ko tồn tại thì thoát ra ko thực hiện lệnh dưới

    exit();
}
dttl_pl_uninstall();
function dttl_pl_uninstall(){
    global $wpdb;
    delete_option('dttl_pl_version');
    delete_option('dttl_pl_option');
    $table_name = $wpdb->prefix . 'dttl_pl_test';
    $sql = "DROP TABLE IF EXISTS " . $table_name;
    $wpdb->query($sql);// thực thi sql bằng đối tượng wpdb
}
?>