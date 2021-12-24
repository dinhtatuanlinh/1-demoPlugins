<?php
class dttl_dashboard_widget {
    // tạo biến private để đặt tên cho transient thông qua hàm set_transient()
    private $_cache_name = '';
    public function __construct(){
        // gắn hàm tạo dashboard vào hook wp_dashboard_setup
        add_action("wp_dashboard_setup", array($this, 'dashboard_widget'));
    }
    public function dashboard_widget(){
        //tham soos thứ nhất của phương thức wp_add_dashboard_widget là id của dashboard sẽ tạo
        $widget_id = 'dttl_pl_dashboard_widget';
        // tham số thứ 2 là title hiển thị của dashboard
        $widget_title= 'dttl pl dashboard widget';
        // tham số thứ 3 là hàm hiển thị ra thông tin trên dashboard

        wp_add_dashboard_widget($widget_id, $widget_title, array($this, 'display_dashboard_function'));
    }
    public function display_dashboard_function(){
        echo '<p>thông tin hiển thị của dashboard<p>';
        // có thể xóa transient cache bằng hàm delete_transient();
        // delete_transient($this->_cache_name);
        $cache_memory = get_transient($this->_cache_name);
        // nếu chưa tạo transient kết quả trả về sẽ là false
        // nếu đã tồn tại sẽ trả về kết quả lưu bằng transient trong bảng options
        if($cache_memory == false){
            echo 'ko dùng cache';
            // lấy các bài viết của tác giả có id bằng 1
            $wp_query = new WP_Query('author=1');


            // lưu dữ liệu bằng set_transient để có thể tăng tốc độ truy cập dữ liệu
            // tham số thứ nhất là tên của transient được lưu trong bảng options
            // tham số thứ 2 là dữ liệu được lưu trong transient
            // tham số thứ 3 là thời gian tồn tại của 
            // có các hằng số là đơn vị thời gian
            // như: MINITE_IN_SECOND, HOUR_IN_SECOND, DAY_IN_SECOND, WEEK_IN_SECOND, YEAR_IN_SECOND
            // ở đây ví dụ lự trong 1 giờ
            set_transient($this->_cache_name, $wp_query, 1*HOUR_IN_SECONDS);
        }else{
            echo 'dùng cache';
            $wp_query = $cache_memory;
        }
        
        $link_edit_post = '#';
        if($wp_query->have_posts()){
            echo '<ul>';
            while($wp_query->have_posts()){
                $wp_query->the_post();
                // phương thức admin_url sẽ lấy link tới folder wp_admin/ rồi gắn tiếp vào giá trị tham số truyền vào
                $link_edit_post= admin_url('post.php?post='.get_the_ID().'&action=edit');
                echo '<li><a href="'.$link_edit_post.'">'.get_the_title().'</a></li>';
            }
            echo '</ul>';
        }
        wp_reset_postdata();
    }
}
?>