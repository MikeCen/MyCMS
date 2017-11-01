<?php 
namespace Admin\Controller;
use Think\Controller;

class MenuController extends Controller{
	public function __construct(){
		parent::__construct();
		if (!session('?adminUser')) {
			$this->error('请先登录！','/index.php?m=admin&c=login&a=index');
		}
	}
	public function index(){
		$data = array();
		//类型选择
		if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], array(0,1))) {
			$data['type'] = intval($_REQUEST['type']);
			$this->assign('type',$data['type']);
		}else{
			$this->assign('type',-1);
		}

		$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
		$pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 6;
		$menus = D('Menu')->getMenus($data,$page,$pageSize);
		$menusCount = D('Menu')->getMenusCount($data);
		$res = new \Think\Page($menusCount,$pageSize);
		$pageRes = $res->show();
		$this->assign('pageRes',$pageRes);   //页码
		$this->assign('menus',$menus);      //页数据
		$this->display(); 
	}

	public function add(){
		if ($_POST) {
			if (!isset($_POST['name']) || !$_POST['name']) {
				return show(0,'菜单名不能为空');
			}
			if (!isset($_POST['m']) || !$_POST['m']) {
				return show(0,'模块名不能为空');
			}
			if (!isset($_POST['c']) || !$_POST['c']) {
				return show(0,'控制器名不能为空');
			}
			if (!isset($_POST['f']) || !$_POST['f']) {
				return show(0,'方法名不能为空');
			}

			if ($_POST['menu_id']) {
				return $this->save($_POST);
			}
			
			$res = D('Menu')->addMenu($_POST);
			if ($res == 0) {
				return show(0,'菜单不是数组！');
			}
			
			if ($res) {
				return show(1,'添加菜单成功！');
			}
			return show(0,'添加菜单失败！');
		}else{
			$this->display();
		}
	}

	public function edit(){
		$menuId = $_GET['id'];
		$menu = D('Menu')->find($menuId);
		$this->assign('menu',$menu);
		$this->display();
	}

	public function save($data){
		$menuId = $data['menu_id'];
		unset($data['menu_id']);
		try{
			$id = D('Menu')->updateMenuById($menuId,$data);
			if ($id === false) {
				return show(0,'更新失败！');
			} else {
				return show(1,'更新成功！');
			}
		}catch(Exception $e){
			return show(0,$e->getMessage());
		}
	}

	public function setStatus(){
		try{
			if ($_POST) {
				$id = $_POST['id'];
				$status = $_POST['status'];
				$res = D('Menu')->setStatusById($id,$status);
				if ($res) {
					return show(1,'操作成功！');
				}else{
					return show(0,'操作失败！');
				}
			}
		}catch(Exception $e){
			return show(0,$e->getMessage());
		}
		return show(0,'未提交任何数据！');
	}

	public function listorder(){
		$listorder = $_POST;
		$jumpUrl = $_SERVER['HTTP_REFERER'];
		$errors = array();
		if ($listorder) {
			try{
				foreach ($listorder as $key => $value) {
					$id = D("Menu")->updateMenuListorderById($key,$value);
					if ($id === false) {
						$errors[] = $key;
					}
				}
			}catch(Exception $e){
				return show(0,$e->getMessage(),array('jump_url'=>$jumpUrl));
			}
			
			if ($errors) {
				return show(0,'排序失败-'.implode(',', $errors),array('jump_url'=>$jumpUrl));
			}
			return show(1,'排序成功！',array('jump_url'=>$jumpUrl));
		}
		return show(0,'排序数据失败！',array('jump_url'=>$jumpUrl));
	}


}