<?php
$_SERVER['HTTP_HOST']='localhost:8080'; require '/home/claude/wptest/wp-load.php'; wp_set_current_user(1);
$hub = get_page_by_path('vao-vu'); $hid=$hub->ID;
update_post_meta($hid,'_wp_page_template','page-event-hub.php');
wp_update_post(array('ID'=>$hid,'post_content'=>'Mùa vụ mới bắt đầu rồi! Chọn một hoạt động để gửi lời động viên đến những đồng đội đang ngày ngày bám ruộng, bám ca.'));
$kids=array(
 array('Lời tiếp sức','loi-tiep-suc','page-thanks.php','Gửi lời cảm ơn, lời động viên tới đồng đội đang ở tuyến đầu mùa vụ.'),
 array('Bưu thiếp mùa vụ','buu-thiep','page-postcard.php','Chọn một khoảnh khắc đẹp, viết lời chúc và tải bưu thiếp về gửi đồng đội.'),
 array('Khoác áo Pit Crew','khoac-ao-pit-crew','page-avatar.php','Đổi avatar với khung Pitstop để cả đội cùng một màu áo.'),
 array('Khoảnh khắc đội mình','khoanh-khac','page-contest.php','Mùa vụ là lúc cả AgriS cùng vào việc. Người bám ruộng từ sớm, người đứng máy xuyên ca, người chạy từng chuyến xe mía về nhà máy, người mang sản phẩm đến tay khách hàng, và những đồng đội lặng lẽ hỗ trợ phía sau. Hãy bắt lại những khoảnh khắc đội mình cùng nhau làm nên mùa vụ, từ ruộng mía, nhà máy, kho hàng, thị trường đến văn phòng. Những bức ảnh đạt giải sẽ được dùng trong các ấn phẩm truyền thông của Công ty.'),
);
foreach($kids as $i=>$k){ $id=wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>$k[0],'post_name'=>$k[1],'post_parent'=>$hid,'menu_order'=>$i,'post_content'=>$k[3])); update_post_meta($id,'_wp_page_template',$k[2]);
 if($k[2]==='page-thanks.php'){ foreach(get_posts(array('post_type'=>'loi_cam_on','post_status'=>'any','numberposts'=>-1)) as $n) update_post_meta($n->ID,'_agm_page',$id);} }
update_option('amv_force_open','1');
echo "ok\n";
