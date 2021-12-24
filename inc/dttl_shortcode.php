<?php
class dttl_shortcode {
    private $_shortcode_name = 'dttl_shortcode';
    private $_shortcode_option = array();
    public function __construct(){

        // nên tạo ra 1 menu quản trị shortcode
        // chỉ shortcode được kích hoạt trong menu mới sử dụng được
        // trong menu đó sẽ set giá trị trong bảng options
        // lấy giá trị lưu trong bảng option với tên là $_shortcode_name
        // tham số thứ 2 là giá trị nếu ko tìm thấy trong bảng options
        $defaultOption = array(
            'dttl_sc_date' => false,
            'dttl_sc_title'=> true
        );
        
        $this->_shortcode_option = get_option($this->_shortcode_name, $defaultOption);
        if($this->_shortcode_option['dttl_sc_date']){

            // tạo short code
            add_shortcode('dttl_sc_date', array($this, 'dttl_sc_show_date'));
            // cách sử dụng shortcode ở bài viết
            // thêm [dttl_sc_date] vào vị trí muốn hiển thị shortcode này
        }else{
            //nên xóa short code bằng cách này để loại bỏ những shortcode đã được thêm cả vào bài viết
            add_shortcode('dttl_sc_date', '__return_false');
        }
        // phương thức remove_shortcode() giúp xóa đi shortcode đã được tạo
        // kiểm tra shortcode đã được đăng ký chưa bằng hàm shortcode_exists('tên shortcode'); nếu shortcode đang được sử dụng trả về 1 nếu chưa thì ko trả gì cả
        // kiểm tra 1 nội dung có được thêm shortcode đó không has_shortcode('nội dung cần kiểm tra xem có shortcode ko','tên shortcode'); nếu có trả về 1
        if($this->_shortcode_option['dttl_sc_title']){

            // tạo short code
            add_shortcode('dttl_sc_title', array($this, 'dttl_sc_title'));
            
            // cách sử dụng shortcode ở bài viết có đưa tham số vào
            // thêm [dttl_sc_title ids='47,48,30' title='cac bai viet lien quan'] vào vị trí muốn hiển thị shortcode này
        }else{
            //nên xóa short code bằng cách này để loại bỏ những shortcode đã được thêm cả vào bài viết
            add_shortcode('dttl_sc_title', '__return_false');
        }
        // add_action('the_content', array($this,'remove_all_sc'));
        add_action('the_content', array($this,'get_shortcode_regex'));
    }
    public function get_shortcode_regex($content){
        // hàm get_shortcode_regex() giúp kiểm tra các shortcode đang dùng trong content
        $pattern = '/'. get_shortcode_regex().'/s';
        preg_match_all($pattern, $content, $matches);
        // hàm array_key_exists() kiểm tra mảng trong tham số thứ 2 có phần tử 2 không phần tử thứ 2 là mảng chứa tên các shortcode
        if(array_key_exists(2, $matches)){
            $shortcodeArr = $matches[2];
        }
        return $content;
    }
    public function remove_all_sc($content){
        // hàm strip_shortcodes() giúp loại bỏ toàn bộ shortcode có trong content (không nên dùng)
        $content= strip_shortcodes($content);
        return $content;
    }
    public function dttl_sc_show_date(){
        $str = date('l jS \of F Y h:i:s A');
        return $str;
    }
    public function dttl_sc_title($atts){
        if (is_single()){
            
            // thêm các tham số mặc định vào biên $atts bằng phương thức shortcode_atts() nếu ko có tham số tuyền vào sẽ dùng các tham số này
            $pairs = array(
                'ids' => '1',
                'title' => 'cac bai lien quan'
            );
            // tham số thứ nhất là các tham số muốn làm mặc định tham số thứ 2 là tham số truyền qua shortcode
            // tham số thứ 3 là tên shortcode
            $atts = shortcode_atts($pairs, $atts, 'dttl_sc_title');

            extract($atts);
            $ids = explode(',', $ids);
            if(count($ids)>0){
                $args = array(
                    'post_type' => 'post',
                    'post__in'=> $ids,
                    // 'post_status'=> 'publish'
                );
                $wpQuery = new WP_Query($args);
                if($wpQuery->have_posts()){
                    $list = '<ul>';
                    while($wpQuery->have_posts()){
                        $wpQuery->the_post();
                        $link = $wpQuery->post->guid;
                        $list .= '<li><a href="'.$link.'">'.$wpQuery->post->post_title.'</a></li>';
                    }
                    $list .= '</ul>';
                }

                wp_reset_postdata();
            }

            $html = '<div><b>'.$title.'</b>'.$list.'</div>';
            return $html;
        }
    }
}
?>