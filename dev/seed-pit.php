<?php
$_SERVER['HTTP_HOST']='localhost:8080'; require '/home/claude/wptest/wp-load.php'; wp_set_current_user(1);
$hub=get_page_by_path('vao-vu');
$id=wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>'Đăng ký Pit Crew','post_name'=>'pit-crew','post_parent'=>$hub->ID,'menu_order'=>-1,'post_excerpt'=>'Cùng đoàn HO ra tận trạm PIT ở nhà máy và vùng nguyên liệu, tiếp sức cho đội vào vụ.','post_content'=>'Pit Crew là đội tiếp sức đi cùng đoàn HO tới các nhà máy và vùng nguyên liệu mùa Vào vụ. Chọn tour bạn muốn tham gia, ban tổ chức sẽ xét chọn và báo kết quả.'));
update_post_meta($id,'_wp_page_template','page-pitcrew.php'); echo "$id\n";
