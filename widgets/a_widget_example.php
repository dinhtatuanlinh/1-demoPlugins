<?php
// tạo 1 widget example
// tạo lớp chứa 4 thuộc tính
// __construct, widget, form, update
class dttl_pl_a_widget_example extends WP_Widget{
    public function __construct(){
        // goi vào hàm __construct của lớp cha WP_Widget có các tham số sau
        // tham số thứ nhất là id của widget
        $id_base="dttl-pl-a-widget-example";
        // tham số thứ 2 là tên của widget
        $name = "a widget example";
        // tham số thứ 3 là mảng chứa class của widget vào mô tả cho widget
        $widget_options = array(
            'classname'=>'dttl-pl-a-wg-ex',
            'description'=> 'Tạo widget ví dụ'
        );
        // tham số thứ 4 là tham số cài đặt chiều rộng và chiều cao cho widget sau khi kéo vào vị trí hiển thị trong menu widget
        $control_options = array(
            'width'=>"250px",// 250px là chiều rộng mặc định của các widget trong hệ thông, nên để 250px
            'height'=>"500px"// tham số này chưa có tác dụng do vậy ko cần truyền cũng được
        );
        parent::__construct($id_base,$name, $widget_options,$control_options);
        // phương thức is_active_widget để kiểm tra widget đã được thêm vào hay chưa nếu đã được thêm vào thì css và js mới được kéo vào
        if(!empty(is_active_widget(false, false, $id_base, true))){
            // cách đưa css vào thông qua action hook wp_head
            // add_action('wp_head', array($this,'add_css'))
            // cách 2 để đưa css vào
            add_action( 'wp_enqueue_scripts', array($this,'add_css'));
        }
        
    }
    public function add_css(){
        // cách 1 là đưa thẻ link vào action hook wp_head
        // $csslink = DTTL_PL_CSS_DIR . "pl-demo.css";
        // $output = '<link rel="stylesheet" href="'.$csslink.'" type="text/css" media="all">';
        // echo $output;
        wp_register_style('pl-demo', DTTL_PL_CSS_DIR . "pl-demo.css", array(), '1.1');
        if(is_front_page()){// nếu ở trang chủ mới kéo file css vào
            wp_enqueue_style('pl-demo');
        }else if(is_page()){// trong trường hợp là trang page

        }
        
    }
    // phương thức widget để hiện thị ngoài frontend
    public function widget($args,$instance){
        // echo '<pre>';
        // print_r($args);
        // echo '</pre>';
        // phương thức extract sẽ convert mảng ra thành từng biến riêng lẻ theo key
        extract($args);
        // echo '<br>'. $name;
        // echo '<br>'. $id;
        // bên trong $args có các key sau name, id, description, class, before_widget, after_widget, before_title, after_title, before_sidebar, after_sidebar, widget_id, widget_name
        // before_widget, after_widget, before_title, after_title, before_sidebar, after_sidebar là các thẻ html dùng để bọc các thứ mình muốn bọc
        // before_widget, after_widget là thẻ li chứa class của widget truyền vào trên hàm construct ở trên
        // before_title, after_title là thẻ h3
        // filter widget_title giúp lọc các chuỗi ko cần thiết được truyền vào
        $title = apply_filters('widget_title',$instance['title']);
        $title = (empty($title))?'title rỗng': $title;
        // &nbsp là ký hiệu khoảng trắng
        $movie = (empty($instance['movie']))?'&nbsp': $instance['movie'];
        echo $before_title . $title .$after_title;
        echo $before_title . $movie .$after_title;
        echo '<pre>';
        print_r($instance);
        echo '</pre>';

    }
    // tạo form nhập liệu cho widget bằng hàm form
    public function form($instance){
        // biến instance là giá trị đã được lưu trong bảng options sau khi lưu giá trị

        // tạo 1 ô nhập liệu cho widget
        // dùng phương thức $this->get_field_id('tilte') để tạo ra id của ô input phương thức này sẽ lấy id của widget cộng với vị trí của ô widget khi kéo vào vị trí hiện thị trên website
        // phương thức $this->get_field_name('title') để tạo ra name của ô input tương tự lấy id của widget cộng với vị trí cộng với chữ title là tham số truyền vào get_field_name
        // class widefat là class mặc định để css cho ô input
        // vị trí for của lable cũng dùng get_field_id để tạo
        $htmlObj = new PLZendvnHtml();
        $fieldName = 'title';
        $inputID = $this->get_field_id($fieldName);
        $inputName =$this->get_field_name($fieldName);
        // thêm @ để loại bỏ đi cảnh báo khi biến instance chưa được tạo
        $inputValue = @$instance['title'];// sau khi lưu giá trị thì biến instance sẽ trả về giá trị để đưa vào ô input
        $arr = array(
            'class'=> 'widefat',
            'id'=> $inputID ,
        );
        // tham số thứ nhât của phương thức textbox là thuộc tính name của ô textbox

        echo '<p><label for="' .$inputID.'">' . translate('Title' ,$domain ='default').':</label>'.
        $htmlObj->textbox($inputName ,$titleValue, $arr).
        '</p>';
        // tạo thêm phần tử input khác
        $fieldName = 'movie';
        $inputID = $this->get_field_id($fieldName);
        $inputName =$this->get_field_name($fieldName);
        $inputValue = @$instance['movie'];
        $arr = array(
            'class'=> 'widefat',
            'id'=> $inputID ,
        );
        // tham số thứ nhât của phương thức textbox là thuộc tính name của ô textbox

        echo '<p><label for="' .$inputID.'">' . translate('Movie' ,$domain ='default').':</label>'.
        $htmlObj->textbox($inputName ,$titleValue, $arr).
        '</p>';
    }
    // hàm update để lưu các giá trị truyền vào qua các ô input trong func form
    public function update($new_instance, $old_instance){
        // echo '<pre>';
        // print_r($new_instance);
        // echo '</pre>';
        // hàm die để dùng lại kiểm tra giá trị in ra
        // die();
        // gán các giá trị cũ trước khi đưa giá trị mới vào
        $instance = $old_instance;
        // hàm strip_tags để loại bỏ các thẻ html
        $instance['title'] = $new_instance['title'];
        $instance['movie'] = $new_instance['movie'];

        // return mảng instance để lưu vào database
        return $instance;
    }
}
?>