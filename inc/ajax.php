<?php
class DttlMpSettingAjax {
    // 
    private $_menuSlug = 'dttlSettingAjax';
    private $_optionName = 'dttlSettingAjaxOptionName';
    private $_settingOptions;
    public function __construct(){
        // echo '<br>' . __CLASS__; // lấy tên class
        $this->_settingOptions= get_option($this->_optionName);

        add_action('admin_menu', array($this, 'settingMenu'));

        add_action('admin_init', array($this, 'register_setting_fields'));
    }
    // 8. register setting
    // tạo các ô input theo chuẩn wordpress(đăng ký một setting)
    public function register_setting_fields(){
        
        // tham số thứ 2 là tên được đưa vào cột option name của bảng options
        register_setting( $this->_menuSlug, $this->_optionName, array($this, 'validate_setting') ); 
        $mainSection = 'dttlSettingAjax';
        add_settings_section(
            $mainSection, 
            'dttl ajax', 
            array($this, 'main_section_view'), 
            $this->_menuSlug);// tạo ra 1 section với tham số thứ nhất là id của section, tham số thứ 2 là title của section
        // tham số thứ 3 là hàm thực hiện, tham số thứ 4 là slug của menu

        add_settings_field( 
            'dttl_pl_field', 
            'my ajax', 
            array($this, 'createForm'), 
            $this->_menuSlug, 
            $mainSection,
            array('name' => 'ajax')
         );
        // phương thức add_setting_field với tham số thứ nhất là id của field tham số thứ 3 là nhãn của field tham số thứ 2 là hàm được gọi ra để tạo ô input của field tham số thứ 5 là slug của menu
        // tham số thứ 6 là id của section chứa field
        // add_settings_field( 
        //     'dttl_pl_field-2', 
        //     'my field 2' , 
        //     array($this, 'new_title_input_2'), 
        //     $this->_menuSlug, 
        //     'abc'); 
            // khi tham số thứ 5 được đặt là 1 str bất kỳ thì sẽ sử dụng phương thức do_setting_fields 
            // để hiển thị nó vào vị trí mong muốn với tham số thứ 5 ứng với tham số thứ 2 của do setting fields
        // 
    }
    private function createId($val){
        return $this->_optionName . '_' . $val;
    }
    private function createName($val){
        return $this->_optionName . '[' . $val . ']';
    }
    public function createForm(){
        echo '<br>' . __METHOD__; 
        $htmlObj = new PLZendvnHtml();
        if($args['name'] == 'ajax'){
            $name = $this->createName('title');
            $value = @$this->_settingOptions['title'];
            $arr = array(
                'id'=> $this->createId('title'),
                'class'=>'abc',
                'style'=> 'width: 300px'
            );
            echo $htmlObj->textbox('dttl_pl_name[dttl_pl_new_title]','this is a text', $arr) . 
            $htmlObj->pTag('Nhập vào 1 chuỗi', array('class'=>'description'));
    
        }
        
    }
    public function main_section_view(){

    }
    // 1. thêm 1 submenu vào dashboard menu
    public function settingMenu(){
        // add 1 submenu vào menu dashboard tham số thứ 3 là phân quyền cho những user có thể truy cập
        add_dashboard_page(
            'my menu title', 
            'My Ajax', 
            // manage_options là phân quyền cho user có thể truy cập
            'manage_options', 
            $this->_menuSlug, 
            array($this, 'display'));

    }

    public function display(){
        // kéo vào giao diện của menu này
        require DTTL_PL_VIEWS_DIR . '/display.php';
    }

}