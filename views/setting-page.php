<div class="wrap">
    <?php
        settings_errors( $this->_menuSlug, false, false ); // hiển thị thông báo của add_settings_errors (chú ký chỉ dùng ở menu là menu chính)
    ?>
    <h2>my menu</h2>
    <p>đây là test thêm dữ liệu vào option table</p>
    <form method='post' action="options.php" id="dttl-demoPlugin-form-setting" enctype="multipart/form-data">
    <!-- action là options.php để wordpress tự động lưu dữ liệu vào bảng options -->
        <?php echo settings_fields('dttl_pl_options'); ?> 
        <!-- tạo ra các trường ẩn mặc định của wordpress -->
        <!-- tham số đưa vào là tham số đầu tiên của phương thức register_setting -->
        <?php echo do_settings_sections( $this->_menuSlug ); ?>
        <!-- phương thức này hiển thị section ra ngoài trang menu -->
        <?php do_settings_fields($this->_menuSlug, 'abc'); ?>
        <?php
            global $wpdb;
            $table = $wpdb->prefix . 'options';
            $query = "SELECT * FROM {$table} WHERE autoload='no'";
            $output = $wpdb->get_row($query);// chỉ lấy duy nhất 1 dòng trong database
            $outputArr = $wpdb->get_results($query);// trả về nhièu dòng dưới dạng array, tham số thứ 2 là kiểu hiển thị dữ liệu
            $outputcol = $wpdb->get_col($query, 0); // 0 là vị trí cột lấy nếu là 0 là cột đầu tiên
            $tabletest = $wpdb->prefix . 'test';
            $data = array(
                'title'=> 'this is a test',
                'picture' => 'abc.jpg',
                'content' => 'this is content',
                'status' => 1,
            );
            $format =  array('%s','%s','%s', '%d' ); // khai bao kiểu dữ liệu cho từng dòng chuyền vào %s là string, %d là number
            // $outputinsert = $wpdb->insert($tabletest,$data, $format);
            $datareplace = array(
                'id' => 1,
                'title'=> 'this is a test 123',
                'picture' => 'abc.jpg',
                'content' => 'this is content 123',
                'status' => 1,
            );
            $formatreplace = array('%d','%s','%s','%s', '%d' );
            //$outputreplace = $wpdb->replace($tabletest,$datareplace, $formatreplace); // sửa dữ liệu trên 1 dòng chỉ định bởi id, nếu id chưa tồn tại thì sẽ thêm tự động vào dòng
            $dataupdate = array(

                'title'=> 'this is a test 123',
                'picture' => 'abc.jpg',
                'content' => 'this is content 123',
                'status' => 1,
            );
            $formatupdate = array('%s','%s','%s', '%d' );
            $where = array('id' => 1);
            $whereformat = array('%d');// định nghĩa kiểu dữ liệu cho where
            // $outputupdate = $wpdb->update($tabletest,$dataupdate,$where,$formatupdate,$whereformat); // update 1 dòng theo điều kiện where
            //$outputdelete = $wpdb->delete($tabletest, $where, $whereformat); // xóa 1 dòng tại where, nếu dòng ko tồn tại thì kết quả trả về 0
            $queryprepare = "INSERT INTO {$tabletest} (`title`, `picture`, `content`, `status`) VALUES (%s, %s, %s, %d)";
            $titlepre = 'this is title prepare';
            $picturepre = 'abcd.jpg';
            $content = 'contentaf aba';
            $status = 1;
            $outputprepare = $wpdb->prepare($queryprepare, $titlepre, $picturepre, $content, $status); // prepare được sử dụng để lọc dữ liệu tránh sql injection, output trả về là 1 câu lệnh sql
            // $outputquery = $wpdb->query($outputprepare);// câu lệnh này dùng để thực thư câu lệnh sql sau khi được xuất ra từ prepare

            // echo '<pre>';
            // print_r();
            // echo '</pre>';
        ?>
        <p class="submit">
            <input class="button button-primary" type="submit" name="submit" value="Save change">
        </p>
    </form>
</div>
