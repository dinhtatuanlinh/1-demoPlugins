<?php
class dttl_pl_ad{
    private $_menuSlug = 'dttl-pl-my-main-menu';
    private $_setting_options; // biến chứa dữ liệu đang được lưu trong opiton
    public function __construct(){
        // echo '<br>' . __CLASS__; // lấy tên class
        // echo '<br>' . __METHOD__; // lấy tên methode
        add_action('admin_menu', array($this, 'settingMenu'));
        add_action('admin_menu', array($this, 'settingMainMenu'));
        add_action('admin_menu', array($this, 'settingSubMainMenu'));
        add_action('admin_menu', array($this, 'removeMenu'));
        add_action('admin_init', array($this, 'dttl_get_data_from_option'));
        // add_action('admin_init', array($this, 'register_setting_fields'));
        add_action('admin_init', array($this, 'uploadFile'));
        $this->_setting_options['dttl_pl_name'] = get_option('dttl_pl_name');
        $this->_setting_options['dttl_pl_uploadFile'] = get_option('dttl_pl_uploadFile');
        
        // echo '<pre>';
        // print_r($this->_setting_options);
        // echo '</pre>';
    }
    // 9. upload file
    public function uploadFile(){
        register_setting( 'dttl_pl_uploadFile', 'dttl_pl_uploadFile', array($this, 'validate_setting') );// tạo dòng dữ liệu trên bảng option
        $mainSection = 'dttl_pl_uploadFile_section';
        add_settings_section($mainSection, 'dttl upload file', array($this, 'main_section_view'), $this->_menuSlug);
        // add_settings_field( 'dttl_pl_uploadFile_field', 'my uploadFile field', array($this, 'view_uploadFile_input'), $this->_menuSlug, $mainSection );
        add_settings_field( 'dttl_pl_uploadFile_field_2', 'my uploadFile field 2', array($this, 'create_form'), $this->_menuSlug, $mainSection, array('name' => 'new_title_input') );
        add_settings_field( 'dttl_pl_uploadFile_field', 'my uploadFile field', array($this, 'create_form'), $this->_menuSlug, $mainSection, array('name' => 'view_uploadFile_input') );
        
        // tham số cuối cùng của hàm add_settings_field dùng để đưa một biến vào hàm tạo form ở đây là create_form từ tham số này ta có thể in ra bất kỳ input nào mà ko cần gọi thêm hàm
        $tmp = get_settings_errors( $this->_menuSlug );// phương thức get settings errors sẽ lấy dữ liệu từ phương thức add_settings_error (chú ký chỉ dùng ở menu là menu chính)
    }
    public function create_form($args){

        if ($args['name'] == 'view_uploadFile_input'){
            echo '<input type="file" name="dttl_pl_uploadFile" />';
            echo '<p class="description">định dạng file JPG|PNG|GIF</p>';
            if (!empty($this->_setting_options['dttl_pl_name']['dttl_pl_uploadFile'])){// kiểm tra file có tồn tại ko

                echo '<img src="' . $this->_setting_options['dttl_pl_name']['dttl_pl_uploadFile'] . '" width="200">';
            }
        }
        if ($args['name'] == 'new_title_input'){
            echo '<input type="text" name="dttl_pl_name[dttl_pl_new_title]" value="' . $this->_setting_options['dttl_pl_name']['dttl_pl_new_title'] .'"/>';
            echo '<p class="description">nhập ko quá 20 ký tự</p>';
        }
    }
    public function view_uploadFile_input(){
        // echo '<pre>';
        // print_r($this->_setting_options);
        // echo '</pre>';
        echo '<input type="file" name="dttl_pl_uploadFile" />';
        if (!empty($this->_setting_options['dttl_pl_name']['dttl_pl_uploadFile'])){// kiểm tra file có tồn tại ko

            echo '<img src="' . $this->_setting_options['dttl_pl_name']['dttl_pl_uploadFile'] . '" width="200">';
        }
        
    }

    // 8. register setting
    // tạo các ô input theo chuẩn wordpress
    public function register_setting_fields(){
        register_setting( 'dttl_pl_options', 'dttl_pl_name', array($this, 'validate_setting') ); // tham số thứ 2 là tên được đưa vào cột option name của bảng options
        $mainSection = 'dttl_pl_main_section';
        add_settings_section($mainSection, 'dttl main setting', array($this, 'main_section_view'), $this->_menuSlug);// tạo ra 1 section với tham số thứ nhất là id của section, tham số thứ 2 là title của section
        // tham số thứ 3 là hàm thực hiện, tham số thứ 4 là slug của menu
        add_settings_section('dttl_pl_main_section-2', 'dttl main setting 2', array($this, 'main_section_view'), $this->_menuSlug);
        add_settings_field( 'dttl_pl_field', 'my field', array($this, 'new_title_input'), $this->_menuSlug, $mainSection );
        // phương thức add_setting_field với tham số thứ nhất là id của field tham số thứ 3 là nhãn của field tham số thứ 2 là hàm được gọi ra để tạo ô input của field tham số thứ 5 là slug của menu
        // tham số thứ 6 là id của section chứa field
        add_settings_field( 'dttl_pl_field-2', 'my field 2' , array($this, 'new_title_input_2'), $this->_menuSlug, 'abc'); // khi tham số thứ 5 được đặt là 1 str bất kỳ thì sẽ sử dụng hương thức do_setting_fields để hiển thị nó vào vị trí mong muốn với tham số thứ 5 ứng với tham số thứ 2 của do setting fields
        // 
    }
    public function new_title_input_2(){

        echo '<input type="checkbox" name="dttl_pl_name[dttl_pl_new_title_2]" value=""/>';
    }
    public function new_title_input(){
        echo '<input type="text" name="dttl_pl_name[dttl_pl_new_title]" value="' . $this->_setting_options['dttl_pl_name']['dttl_pl_new_title'] .'"/>';
    }
    public function main_section_view(){

    }
    // kiêm tra chiều dài của chuỗi
    private function stringMaxValidate($val, $max){
        $flag = false;
        $str = trim($val);
        if (strlen($str) <= $max){
            $flag = true;
        }
        return $flag;
    }
    // kiểm tra phần mở rộng của file
    private function fileExtionsValidate($file_name, $file_type){
        $flag = false;
        $pattern = '/^.*\.(' . strtolower($file_type) . ')$/i'; // $file_type = JPG|PNG|GIF
        if(preg_match($pattern, strtolower($file_name)) == 1){
            $flag = true;
        }
        return $flag;
    }
    public function validate_setting($data_input){ // tham số đưa vào là dữ liệu đưa vào từ ô input
        // echo '<pre>';
        // print_r($data_input);
        // echo '</pre>';
        // die();
        // kiểm tra chiều dài của chuỗi
        $errors = array();
        if($this->stringMaxValidate($data_input['dttl_pl_new_title'], 20) == false){
            $errors['dttl_pl_new_title'] = 'dữ liệu đưa vào vượt quá số ký tự cho phép';
        }
        // kiểm tra phần mở rộng của file
        if (!empty($_FILES['dttl_pl_uploadFile']['name'])){
            if($this->fileExtionsValidate($_FILES['dttl_pl_uploadFile']['name'], 'JPG|PNG|GIF') == false){
                $errors['dttl_pl_uploadFile'] = 'phần mở rộng không đúng với quy định';
            }else{
                if(!empty($this->_setting_options['dttl_pl_name']['dttl_pl_path_uploadFile'])){
                    @unlink($this->_setting_options['dttl_pl_name']['dttl_pl_path_uploadFile']); // hàm unlink() dùng để xóa file đã tồn tại trong uploads @ sẽ giúp ẩn đi lỗi nếu file ko tồn tại
                }
                
                $override = array('test_form'=>false);
                $time = null;// biến này để tạo thư mục chứa file trong thư mục uploads trong wp-content biến này có định dạng 'yyyy/mm/dd' hoặc str str chỉ được phép có 4 ký tự null là để wp tự tạo
                $tmp = wp_handle_upload($_FILES['dttl_pl_uploadFile'], $override, $time); // hàm wp_handle_upload() dùng để upload file lên 
                // $tmp chưa đường dẫn vật ly c:/ và đường dẫn tương đối hostmame/
                // echo '<pre>'
                // print_r($tmp);
                // echo '</pre>';
                $data_input['dttl_pl_uploadFile'] = $tmp['url'];
                $data_input['dttl_pl_path_uploadFile'] = $tmp['file'];
            }
        }else{
            // else rơi vào trường hợp chỉ thay đổi thông tin khác ko phải file upload
            $data_input['dttl_pl_uploadFile'] = $this->_setting_options['dttl_pl_name']['dttl_pl_uploadFile'];
            $data_input['dttl_pl_path_uploadFile'] = $this->_setting_options['dttl_pl_name']['dttl_pl_path_uploadFile'];
        }
        if (count($errors)>0){
            $data_input = $this->_setting_options; // đưa vào data_input giá trị cũ do có lỗi xảy ra
            $strErrors = '';
            // chuyển dữ liệu thành chuỗi rồi gửi ra ngoài để hiện thị
            foreach ($errors as $key => $val){
                $strErrors .= $val . '<br/>';
            }
            add_settings_error( $this->_menuSlug, 'my-setting', 'co loi', 'error' ); // chú ý nếu là 1 menu chính sẽ ko hiển thị lỗi cần sử dụng get_settings_errors để hiện thị ra ngoài
        }else{
            add_settings_error( $this->_menuSlug, 'my-setting', 'success', 'updated' );// hiển thị cập nhật thành công (chỉ dùng khi là menu chính)
        }
        // echo '<pre>';
        // print_r($_FILES);
        // echo '</pre>';
        // die();
        // kiểm tra file upload lên
       
        // hàm này sẽ kiểm tra dữ liệu đầu vào và đưa vào bảng database
        // echo '<pre>';
        // print_r($data_input);
        // echo '</pre>';
        //die();// hàm này dùng để dừng tất cả các hàm tại vị trí nó xuất hiện
        // ============================================================
        // có thể lưu dữ liệu thành nhiều dòng khác nhau trên bảng option bằng hàm update_option() và đối tượng $_POST
        return $data_input;
    }
    // 7. update 1 option trong bảng option
    public function dttl_update_option(){
        update_option('dttl_pl_version', '1.1');// giá trị thứ nhất là option name giá trị thứ 2 là giá trị mới đưa vào

    }
    // 6. get option lấy dữ liệu trong bản option
    public function dttl_get_data_from_option(){
        $tmp = get_option('dttl_pl_version', '3.0');// tham số thử 2 là giá trị trả về khi ko tìm thấy option name dttl_pl_version
        // echo '<br/>' . $tmp;
    }
    // 5. phương thức add_object_page và  add_utiltity_page
    public function removeSysMenu(){
        //add_object_page( $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $function:callable, $icon_url:string );// thêm menu vào dưới menu comment
        //add_utility_page( $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $function:callable, $icon_url:string );// thêm menu vào dưới setting menu
    }
    // 4. remove menu
    public function removeMenu(){
        $menuSlug = 'dttl-pl-my-main-menu';
        $submenuSlug = 'dttl-pl-my-submenu';
        remove_submenu_page($menuSlug, $submenuSlug);
        // nếu muốn xóa đi menu có sẵn thì
        remove_submenu_page('lấy slug của menu chứa menu muốn xóa', 'slug của submenu muốn xóa'); // ấn vào menu đó để lấy slug
    }
    // 3. add submenu vào menu mới tạo
    public function settingSubMainMenu(){
        $menuSlug = 'dttl-pl-my-main-menu';
        $submenuSlug = 'dttl-pl-my-submenu';
        add_menu_page('my main menu title', 'my main menu', 'manage_options', $menuSlug, array($this, 'settingPage'), DTTL_PL_PLUGIN_URL . '/icons/Add-Bag-icon.png', 1);// add 1 menu vào menu chính tham 
        // tham số cuối cùng của hàm add_menu_page là vị trí hiển thị trên menu với 1 là vị trí trên cùng ko được điền trùng với các menu đã có ví dụ dashboard vị trí là 2 nếu trùng sẽ làm mất dashboard
        add_submenu_page($menuSlug, 'my submenu title', 'my submenu', 'manage_options', $submenuSlug, array($this, 'settingSubmenuPage'));
    }
    // 2. add nhóm menu mới vào admin menu
    public function settingMainMenu(){
        $menuSlug = 'dttl-pl-my-main-menu';
        
        add_menu_page('my main menu title 2', 'my main menu 2', 'manage_options', $menuSlug . '-2', array($this, 'settingPage'), DTTL_PL_PLUGIN_URL . '/icons/Bin-Empty-icon.png');// add 1 menu vào menu chính tham
    }
    // 1. thêm 1 submenu vào dashboard menu
    public function settingMenu(){
        $menuSlug = 'dttl-pl-my-menu';
        add_dashboard_page('my menu title', 'my menu', 'manage_options', $menuSlug, array($this, 'settingPage'));// add 1 submenu vào menu dashboard tham số thứ 3 là phân quyền cho những user có thể truy cập
        // add_posts_page('my menu title', 'my menu', 'manage_options', $menuSlug, array($this, 'settingPage')); // add submenu vào post
        // add_media_page();
        // add_comments_page();
        // add_theme_page();
        // add_plugins_page();
        // add_users_page();
        // add_management_page();
        // add_options_page();
    }
    
    public function settingPage(){
        require DTTL_PL_VIEWS_DIR . '/setting-page.php';
    }
    public function settingSubmenuPage(){
        echo '<h2>my submenu</h2>';
    }
}
?>