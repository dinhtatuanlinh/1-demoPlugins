<?php
class dttl_pl_pub{
    public function __construct(){
        // echo '<br>' . __CLASS__; // lấy tên class
        // echo '<br>' . __METHOD__; // lấy tên methode
        add_filter('the_title', array($this, 'theTitle'), 10, 2);// 10 là độ ưu tiền 2 là lấy 2 tham số
        add_filter( 'post_type_link', array($this, 'devvn_remove_slug'), 10, 2 );
    }
    public function theTitle($title, $id){
        $str = 'thay doi tieu de bai viet';
        // echo $str;
        // return $str;
    }
    function devvn_remove_slug( $post_link, $post ) {


        echo '<pre style="color: red">';
        print_r($post);
        echo '</pre>';

        // echo '<pre>';
        // print_r($post);
        // echo '</pre>';
    }
    function dttl_settingMenu(){
        
    }
}
?>