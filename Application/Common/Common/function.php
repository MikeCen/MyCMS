<?php 

/**
 * JSON公用的方法
 */
function show($status,$message,$data=array()){
	$result = array(
			'status' => $status,
			'message' => $message,
			'data' => $data,
		);
	exit(json_encode($result));
}

function getMd5Password($password){
	return md5($password . C('MD5_PRE'));
}

function getMenuType($type){
	return $type == 1 ? '后台菜单' : '前端导航';
}

function menuStatus($status){
	if ($status == 0) {
		return $srt = '关闭';
	}elseif ($status == 1) {
		return $str = '正常';
	}elseif ($status == -1) {
		return $str = '删除';
	}
}

function getNavUrl($nav){
	if ($nav['m'] == 'admin') {
		return $url = '/admin.php?c='.$nav['c'].'&a='.$nav['f'];
	}else{
		return $url = '/index.php?m='.$nav['m'].'&c='.$nav['c'].'&a='.$nav['f'];
	}
}

function getActive($navc){
	$c = strtolower(CONTROLLER_NAME);
	if (strtolower($navc['c'] == $c)) {
		return 'class="active"';
	}
	return '';
}

function showKind($status,$data){
	header('Content-type:application/json;cahrset=utf-8');
	if ($status == 0) {
		exit(json_encode(array('error'=>0,'url'=>$data)));
	}
	exit(json_encode(array('error'=>1,'message'=>$data)));
}

function getLoginUsername(){
	return $_SESSION['adminUser']['username'] ? $_SESSION['adminUser']['username'] : '';
}

function getCatName($navs,$id){
	foreach ($navs as $nav) {
		$navList[$nav['menu_id']] = $nav['name'];
	}
	return isset($navList[$id]) ? $navList[$id] : '未知';
}

function getCopyFrom($type){
	$copyFrom = C('COPY_FROM');
	return $copyFrom[$type] ? $copyFrom[$type] : '未知';
}

function getTuhumb($thumb){
	return $thumb ? '<span style="color:red">有</span>' : '无';
}

function isThumb($thumb){
	return $thumb ? '<span style="color:red">有</span>' : '无';
}

function status($status){
	return $status==1 ? '正常' : '删除';
}