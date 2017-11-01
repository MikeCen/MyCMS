<?php 
namespace Admin\Controller;
use Think\Controller;

class AdminController extends Controller{
	public function __construct(){
		parent::__construct();
		if (!session('?adminUser')) {
			$this->error('请先登录！','/index.php?m=admin&c=login&a=index');
		}
	}
	public function index(){
		$admins = D("Admin")->getAdmins();
		$this->assign('admins',$admins);
		$this->display();
	}

	public function addUser(){
		$data = $_POST;
		if (!$data || !is_array($data)) {
			return show(0,'非法数据！');
		}
		$admin['username'] = $_POST['username'];
		$check = D("Admin")->getAdmins($admin);
		if ($check) {
			return show(0,'用户名已存在，请更改！');
		}
		$res = D("Admin")->addAdmin($data);
		if ($res) {
			return show(1,'添加管理账户成功！');
		}
		return show(0,'添加管理账户失败！');
	}

	public function setStatus(){
		$id = $_POST['id'];
		$status = $_POST['status'];

		if (!is_numeric($id) || !is_numeric($status)) {
			return show(0,'ID或状态非法！');
		}
		try{
			$res = D("Admin")->setStatusById($id,$status);
			if ($res) {
				return  show(1,'更改管理账户状态成功！');
			}
		}catch(Exception $e){
           		return show(0,$e->getMessage());
        		}
		return show(0,'更改管理账户状态失败！');
	}

	public function personal(){
		$vo['username'] = getLoginUsername();
		$res = D("Admin")->getAdminByUserName($vo['username']);
		$this->assign('vo',$vo);
		$this->assign('vo',$res);
		$this->display();
	}

	public function save(){
		$adminId = $_POST['admin_id'];
		$data = $_POST;
		$res = D("Admin")->updateAdminById($adminId,$data);
		if ($res) {
			return show(1,'修改用户个人信息成功！');
		}
		return show(0,'修改用户个人信息失败！');
	}
}