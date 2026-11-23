<?php
$_SERVER['HTTP_HOST']='localhost:8080'; require '/home/claude/wptest/wp-load.php'; wp_set_current_user(1);
$hub = get_page_by_path('vao-vu'); $hid=$hub->ID;
update_post_meta($hid,'_wp_page_template','page-event-hub.php');
wp_update_post(array('ID'=>$hid,'post_content'=>'Mùa vụ là đường đua của cả đội. Chọn một hoạt động để tiếp sức cho đồng đội ở tuyến đầu.'));
$kids=array(
 array('Lời tiếp sức','loi-tiep-suc','page-thanks.php','Gửi lời cảm ơn, lời động viên tới đồng đội đang ở tuyến đầu mùa vụ.'),
 array('Bưu thiếp mùa vụ','buu-thiep','page-postcard.php','Chọn một khoảnh khắc đẹp, viết lời chúc và tải bưu thiếp về gửi đồng đội.'),
 array('Khoác áo Pit Crew','khoac-ao-pit-crew','page-avatar.php','Đổi avatar với khung Pitstop để cả đội cùng một màu áo.'),
 array('Khoảnh khắc Move Action','khoanh-khac','page-contest.php','Gửi ảnh mùa vụ của bạn, rủ đồng đội thả love. Ảnh nhiều love nhất sẽ được vinh danh.'),
);
foreach($kids as $i=>$k){ $id=wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>$k[0],'post_name'=>$k[1],'post_parent'=>$hid,'menu_order'=>$i,'post_content'=>$k[3])); update_post_meta($id,'_wp_page_template',$k[2]);
 if($k[2]==='page-thanks.php'){ foreach(get_posts(array('post_type'=>'loi_cam_on','post_status'=>'any','numberposts'=>-1)) as $n) update_post_meta($n->ID,'_agm_page',$id);} }
update_option('amv_force_open','1');
echo "ok\n";
