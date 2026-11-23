<?php
$_SERVER['HTTP_HOST']='localhost:8080'; require '/home/claude/wptest/wp-load.php';
require_once ABSPATH.'wp-admin/includes/image.php'; require_once ABSPATH.'wp-admin/includes/file.php'; require_once ABSPATH.'wp-admin/includes/media.php';
wp_set_current_user(1);
$tmp=wp_tempnam(); copy('/tmp/vaovu.jpg',$tmp);
$att=media_handle_sideload(array('name'=>'vao-vu-pitstop.jpg','tmp_name'=>$tmp),0);
$page=wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>'Vào vụ 2026–2027','post_name'=>'vao-vu','post_content'=>'Gửi một lời cảm ơn tới đồng đội đang ở tuyến đầu mùa vụ.'));
update_post_meta($page,'_wp_page_template','page-thanks.php'); set_post_thumbnail($page,$att);
$ev=wp_insert_post(array('post_type'=>'su_kien','post_status'=>'publish','post_title'=>'Vào vụ 2026–2027'));
update_post_meta($ev,'_agm_at','2026-11-15T07:00'); update_post_meta($ev,'_agm_end','2027-03-31T23:59'); update_post_meta($ev,'_agm_page',$page); update_post_meta($ev,'_agm_place','Mùa Vui Tết Mía'); set_post_thumbnail($ev,$att);
foreach(array(array('Đội vận chuyển','Minh','Cảm ơn các anh đội xe đã chạy xuyên đêm để mía về nhà máy kịp.'),array('Phòng Nông vụ','','Phòng Nông vụ luôn có mặt ngoài đồng sớm nhất. Trân quý!'),array('Ca 3 AgriP','Hà','Ca đêm nhà máy vất vả rồi, vụ này mình cùng về đích nhé.'),array('chị Thu','An','Cảm ơn chị đã kèm cặp em tuần đầu vào vụ.')) as $n){
 $id=wp_insert_post(array('post_type'=>'loi_cam_on','post_status'=>'publish','post_title'=>'x','post_content'=>$n[2])); update_post_meta($id,'_agm_page',$page); update_post_meta($id,'_agm_to',$n[0]); update_post_meta($id,'_agm_from',$n[1]);}
echo "page $page ev $ev\n";
foreach(agm_event_groups() as $k=>$g) echo $k.': '.count($g)."\n";
