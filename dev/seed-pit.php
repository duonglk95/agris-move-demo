<?php
$_SERVER['HTTP_HOST']='localhost:8080'; require '/home/claude/wptest/wp-load.php'; wp_set_current_user(1);
$hub=get_page_by_path('vao-vu');
$id=wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>'Đăng ký Pit Crew','post_name'=>'pit-crew','post_parent'=>$hub->ID,'menu_order'=>-1,'post_excerpt'=>'Cùng đoàn HO ra tận trạm PIT ở nhà máy và vùng nguyên liệu, tiếp sức cho đội vào vụ.','post_content'=>'Cùng Pit Crew theo chân đoàn HO đến các nhà máy và vùng nguyên liệu để tiếp sức cho đồng đội trong mùa vụ này. Chọn chuyến đi bạn muốn tham gia và gửi đăng ký. Ban tổ chức sẽ xét chọn và thông báo kết quả đến bạn.'));
update_post_meta($id,'_wp_page_template','page-pitcrew.php'); echo "$id\n";
