<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

	public function index(){
		if (session('?adminUser')) {
			$res['countTodayLoginUser'] = D("Admin")->CountTodayLoginUser();
			$res['countNews'] = D("News")->getNewsCount();
			$res['maxReadAmount'] = D("News")->maxReadAmount();
			$res['countPosition'] = D("Position")->countPostion();
			$this->assign('res',$res);
			$this->display();
		}else{
			$this->error('请先登录！','/index.php?m=admin&c=login&a=index');
		}
	}
}