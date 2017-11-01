<?php
/**
 * 推荐位管理
 */

namespace Admin\Controller;
use Think\Controller;

class PositionController extends Controller{
	public function __construct(){
		parent::__construct();
		if (!session('?adminUser')) {
			$this->error('请先登录！','/index.php?m=admin&c=login&a=index');
		}
	}
	public function index(){
		$positions = D('Position')->getList();
		$this->assign('positions',$positions);
		$this->display();
	}

	public function edit(){
		$id = $_GET['id'];
		$vo = D('Position')->find($id);
		$this->assign('vo',$vo);
		$this->display();
	}

	public function save(){
		if (!isset($_POST['name']) || !$_POST['name']) {
			return show(0,'推荐位名称不能为空！');
		}
		if (!isset($_POST['description']) || !$_POST['description']) {
			return show(0,'推荐位描述不能为空！');
		}
		$data = array();
		$id = $_POST['id'];
		$data['description'] = $_POST['description'];
		$data['name'] = $_POST['name'];
		$res = D("Position")->updatePositionById($id,$data);
		if ($res) {
			return show(1,'修改成功！');
		}
		return show(0,'修改失败！');
	}

	public function add(){
		if ($_POST) {
			if (!isset($_POST['name']) || !$_POST['name']) {
				return show(0,'推荐位名称不能为空！');
			}
			if (!isset($_POST['description']) || !$_POST['description']) {
				return show(0,'推荐位描述不能为空！');
			}
			$res = D("Position")->addPosition($_POST);
			if ($res == 0) {
				return show(0,'添加错误！（*数据为非数组）');
			}
			if ($res) {
				return show(1,'添加推荐位成功！');
			}
			return show(0,'添加推荐位失败！');
		}else{
			$this->display();
		}
	}

	public function setStatus(){
		if (!isset($_POST['id']) || !$_POST['id']) {
			return show(0,'ID非法！');
		}
		if (!isset($_POST['status']) || !$_POST['status']) {
			return show(0,'状态非法！');
		}
		$res = D('Position')->setStatus($_POST);
		if ($res) {
			return show(1,'删除成功！');
		}
		return show(0,'删除失败！');
	}
}