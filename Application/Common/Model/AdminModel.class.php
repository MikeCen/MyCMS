<?php 
namespace Common\Model;
use Think\Model;

class AdminModel extends Model{
	private $_db = '';
	public function __construct(){
		$this->$_db = M('admin');
	}
	public function getAdminByUserName($username){
		$condition['username'] = $username;
		$ret = $this->$_db->where($condition)->find();
		return $ret;
	}

	public function getAdmins($where = ''){
		return  $this->$_db->where($where)->select();
	}

	public function addAdmin($data){
		if (!$data || !is_array($data)) {
			throw _Exception("非法传入数据");
		}
		$pwd = $data['password'];
		unset($data['password']);
		$data['password'] = md5($pwd.C('MD5_PRE')) ;
		return $this->$_db->add($data);
	}

	public function setStatusById($id,$status){
		if (!$id || !is_numeric($id)) {
			throw_exception('传入ID非法');
		}
		if (!is_numeric($status)) {
			throw_exception('传入状态非法');
		}
		return $this->$_db->where("admin_id=$id")->save(array('status'=>$status));
	}

	public function updateAdminById($adminId,$data){
		if (!is_numeric($adminId)) {
			throw_exception('用户ID非法');
		}
		return $this->$_db->where("admin_id = $adminId")->save($data);
	}

	public function CountTodayLoginUser(){
		$time = mktime(0,0,0,date("m"),date("d"),date("Y"));
		return  $this->$_db->where("status=1 and lastlogintime > $time")->count();
	}
}