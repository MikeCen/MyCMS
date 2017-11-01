<?php 
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller{
	/**
	 * 错误页面
	 * @author MikeCen <mikecen9@gmail.com>
	 */
	public function error($message = ''){
		$message = $message ? $message : '404 NOT FOUND </br>页面不存在';
		$this->assign('message',$message);
		$this->display("Index/error");
	}
}