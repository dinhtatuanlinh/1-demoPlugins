<?php
class dttl_database_manipulation {
    
    public function __construct(){
    }
    public function get_table(){
        
    }
    public function get_data($wpdb, $tableName, $where, $whereValue, $row_col = 'many',$typeOutput = OBJECT, $index = 0){
        $table = $wpdb->prefix. $tableName;
        // "SELECT * FROM wp-options WHERE autoload = 'yes'"
        // where là điều kiện lấy ví dụ là cột autoload có giá trị là yes
        $query = "SELECT * FROM {$table} WHERE {$where} = '{$whereValue}'";
        $resultCol = $wpdb->get_col($query, 1); 
        if($row_col == 'row'){
            // chỉ lấy duy nhất 1 dòng trong database get_row
            // tham số thứ 2 là kiểu dữ liệu trả về OBJECT là kiểu đối tượng ARRAY_A là kiểu mảng chứa key và value, ARRAY_N là kiểu mảng chứa index và value
            // tham số thứ 3 là vị trí row muốn lấy bắt đầu từ 0
            $result = $wpdb->get_row($query, $output, $index );
        }else if($row_col == 'col'){
            // 0 là vị trí cột lấy nếu là 0 là cột đầu tiên
            $result = $wpdb->get_col($query, 1);
        }else if($row_col == 'many'){
            // trả về nhièu dòng dưới dạng array, tham số thứ 2 là kiểu hiển thị dữ liệu
            $result = $wpdb->get_results($query,$typeOutput);
        }
        return $result;

    }
    public function insert_data($wpdb,$tableName, $data, $format){
        // $data là 1 mảng có chưa key và value
        // kết quả trả về là 1 nếu insert thành công
        // $format là 1 mảng kiểu array('%s', '%s', '%s', '%d') để định dạng cho dữ liêu truyền vào ví dụ phần tử thứ nhất của data có kiểu string thì format đăt %s, số là %d
        $table = $wpdb->prefix. $tableName;
        // $data = array(
        //     'title'=> 'this is a test',
        //         'picture' => 'abc.jpg',
        //         'content' => 'this is content',
        //         'status' => 1,
        // );
        // $format =  array('%s','%s','%s', '%d' );
        $result=$wpdb->insert($table, $data, $format);
    }
    public function replace_data($wpdb,$tableName,$format){
        // sửa dòng đã tồn tại, hoặc thêm mới nếu dòng đó chưa tồn tại
        $table = $wpdb->prefix. $tableName;
        // $data = array(
        // 'id'=> 18, // đối với replace cần có id để chỉ định vị trí sửa dữ liệu
        //     'title'=> 'this is a test',
        //         'picture' => 'abc.jpg',
        //         'content' => 'this is content',
        //         'status' => 1,
        // );
        $result = $wpdb->replace($table, $data, $format);
        // nếu thành cống kết quả trả về sẽ bằng 1
    }
    public function update_data($wpdb,$table,$data,$format,$where,$whereformat){
        // where xác định các điều kiện để update dữ liệu nếu bài viết có tên abc thì có thể đặt where 'name'=>'abc'
        // where không thể đưa nhiều điều kiện
        // $where = array(
        //     'id'=> 20
        // );
        // $format = array('%s','%s','%s', '%d' );
        // $whereformat = array('%d');// định dạng cho điều kiện where
        $table = $wpdb->prefix. $tableName;
        $result = $wpdb->update($table,$data,$where,$format,$whereformat); 
        // nếu thành cống kết quả trả về sẽ bằng 1
    }
    public function delete_data($wpdb,$table,$where,$whereformat){
        $table = $wpdb->prefix. $tableName;
        // $where = array(
        //     'id'=> 20
        // );
        // $whereformat = array('%d');// định dạng cho điều kiện where
        $result = $wpdb->delete($table, $where, $whereformat);
        // nếu thành cống kết quả trả về sẽ bằng 1
        // nếu thất bại kết quả trả về là 0
    }
    public function query($wpdb, $query, $table){
        $query="INSERT INTO {$table} (`title`, `picture`, `content`, `status`) VALUES (%s, %s, %s, %d)";
        // hàm prepare để lọc câu lệnh sql tránh sql injection
        $title = '..';
        $picture = '...';
        $content = '...';
        $status = '...';
        // các tham số tiếp theo là giá trị đứa vào %s, và %d ở trên để định dạng cho giá trị đưa vào
        $prepareQuery = $wpdb->prepare($query, $title, $picture, $content, $status);
        // $prepareQuery là câu lệnh sql tra ra sau khi lọc sql injection
        // câu lệnh sql sau khi prepare sẽ sử dụng hàm $wpdb->query() để thực thi câu lệnh sql sau khi prepare
        $result = $wpdb->query($prepareQuery);
    }
    public function wp_table_access(){
        $wpdb->posts;
        // truy cập vào bảng posts $wpdb->posts truy cập vào bảng option $wpdb->options ...
    }
}
?>