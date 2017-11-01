<?php 
/**
 * 推荐位内容管理
 */
namespace Admin\Controller;
use Think\Controller;

class PositioncontentController extends Controller{
	public function __construct(){
		parent::__construct();
		if (!session('?adminUser')) {
			$this->error('请先登录！','/index.php?m=admin&c=login&a=index');
		}
	}
	public function index(){
		$keyWord =  $_GET['title'];
		$positionId = $_GET['position_id'];
		if ($positionId) {
			$this->assign('positionId',$positionId);
		}else{
			$this->assign('positionId',-1);
		}
		if ($keyWord) {
			$this->assign('titleEnter',$keyWord);
		}else{
			$this->assign('titleEnter','文章标题');
		}
		$this->assign('positions',D("Position")->getPositionMenus());
		$this->assign('contents',D("PositionContent")->getPositionContentList($keyWord,$positionId));
		$this->display();
	}

	public function add(){
		if ($_POST) {
			if (!isset($_POST['title']) || !$_POST['title']) {
				return show(0,'标题不存在！');
			}
			if (!isset($_POST['position_id']) || !$_POST['position_id']) {
				return show(0,'推荐位未选择！');
			}
			if (!isset($_POST['url']) || !$_POST['url']) {
				return show(0,'URL不存在！');
			}
			$pId = $_POST['id'];
			if ($pId) {
				return $this->save($pId,$_POST);
			}
			$rId = D("PositionContent")->insert($_POST);
			if ($rId) {
				return show(1,'添加成功！');
			}else{
				return show(0,'添加失败！');
			}
		}else{
			$this->assign('positions',D("Position")->getPositionMenus());
			$this->display();
		}
	}

	public function save($id,$data){
		if (!$id || !is_numeric($id)) {
			return show(0,'ID非法！');
		}
		if (!$data || !isset($data)) {
			return show(0,'数据非法！');
		}
		unset($data['id']);
		$rId = D("PositionContent")->updatePositionContentById($id,$data);
		if ($rId) {
			return show(1, '修改成功！');
		}else{
			return show(0,'修改失败！');
		}

	}

	public function setStatus(){
		if (!$_POST) {
			return show(0,'未传送任何数据(！)');
		}
		$id = $_POST['id'];
		$status = $_POST['status'];
		if (!$id || !is_numeric($id)) {
			return show(0,'ID非法！');
		}
		if ($status == 0) {
			return show(0,'此推荐位内容为正常状态，无需修改！');
		}
		try{
			$res = D("PositionContent")->setStatus($id,$status);
			if ($res !== false) {
				return show(1,'修改状态成功！');
			}else{
				return show(0,'修改状态失败！');
			}
		}catch(Exception $e){
			return show(0,$e->getMessage());
		}
		return show(0,'修改状态彻底失败！');
	}

	public function edit(){
		$id = $_GET['id'];
		$contents = D("PositionContent")->getContent($id);
		$vo['title'] = $contents['title'];
		$vo['thumb'] = $contents['thumb'];
		$vo['id'] = $id;
		$vo['position_id'] = $contents['position_id'];
		$vo['status'] = $contents['status'];
		$vo['url'] = $contents['url'];
		$vo['thumb'] = $contents['thumb'];
		$positions = D("Position")->getPositionMenus();
		$this->assign('positions',$positions);
		$this->assign('vo',$vo);
		$this->display();
	}

	public function search(){
		$keyWords = $_GET['title'];
		$res = D('PositionContent')->searchPositionContent($keyWords);
		$this->assign('contents',$res);
		$this->display();
	}

	public function listorder(){
		$listorder = $_POST['listorder'];
		$jumpUrl = $_SERVER['HTTP_REFERER'];
		try{
			foreach ($listorder as $id => $orderId) {
				$res = D("PositionContent")->updateListorderById($id,$orderId);
				if ($res === false) {
					return show(0,'排序失败！',array('jump_url'=>$jumpUrl));
				}
			}
			return show(1,'排序成功！',array('jump_url'=>$jumpUrl));
		}catch(Exception $e){
			return show(0,$e->getMessage());
		}
		return show(0,'排序彻底失败！',array('jump_url'=>$jumpUrl));
	}
} 