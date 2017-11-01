<?php
namespace Common\Model;
use Think\Model;

class PositionContentModel extends Model {
	private $_db = '';

	public function __construct() {
		$this->$_db = M('position_content');
	}

	public function getPositionContentList($keyWord,$positionId){
		if (($keyWord && isset($keyWord)) || ($positionId && is_numeric($positionId))) {
			$where['title'] = array('like','%'.$keyWord.'%');
			$where['position_id'] = $positionId;
			$res = $this->$_db
			->where($where)	//通过关键词搜索推荐位内容
			->order('listorder desc,id desc ,create_time desc')
			->select();
		}else{
			$res = $this->$_db
			->order('listorder desc,id desc ,create_time desc')
			->select();
		}
		return $res;
	}

	/**
	 * 修改状态
	 */
	public function setStatus($id,$status){
		if (!id || !is_numeric($id)) {
			throw_exception('ID不合法！');
		}
		$data['status'] =$status;
		return $this->$_db->where("id=$id")->save($data);
	}
	
	/**
	 * 为edit.html获取内容
	 */
	public function getContent($id){
		if (!id || !is_numeric($id)) {
			throw_exception('ID不合法！');
		}
		return $this->$_db->where("id=$id")->find();
	}

	public function updateListorderById($id,$orderId){
		if (!$id || !is_numeric($id)) {
			throw_exception('ID不合法！');
		}
		$data = array('listorder'=>intval($orderId));
		return $this->$_db->where("id=$id")->save($data);
	}

	 /**
         * 文章推送
     	*/
	    public function insert($data = array()){
	    	if (!is_array($data) || !$data ) {
	    		return 0;
	    	}
	    	$data['create_time'] = time();
	    	$data['username'] = getLoginUsername();
	    	return $this->$_db->add($data);
	    }

	    public function updatePositionContentById($id,$data){
	    	if (!id || !is_numeric($id)) {
			throw_exception('ID不合法！');
		}
	    	if (!is_array($data) || !$data ) {
	    		throw_exception('数据不合法！');
	    	}
	    	$data['update_time'] = time();
	    	return $this->$_db->where("id = $id")->save($data);
	    }
}
