<?php 
namespace Admin\Controller;
use Think\Controller;

class BasicController extends Controller{
	public function __construct(){
		parent::__construct();
		if (!session('?adminUser')) {
			$this->error('请先登录！','/index.php?m=admin&c=login&a=index');
		}
	}
	
	public function index(){
		$data = D("Basic")->select();
		$this->assign('vo',$data);
		$this->display();
	}

	public function  add(){
		if ($_POST) {
			if (!$_POST['title']) {
				return show(0,'请填写站点标题！');
			}
			if (!$_POST['keywords']) {
				return show(0,'请填写站点关键词！');
			}
			if (!$_POST['description']) {
				return show(0,'请填写站点描述！');
			}
			D("Basic")->save($_POST);
			return show(1,'配置成功！');
		}else{
			return show(0,'未提交数据！');
		}
	}


}