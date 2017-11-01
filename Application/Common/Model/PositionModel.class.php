<?php 
namespace Common\Model;
use Think\Model;

class PositionModel extends Model{
	private $_db = '';
	public function __construct(){
		$this->$_db = M('position');
	}

	public function getList(){
		$list = $this->$_db->order('id')->select();
		return $list;
	}

	public function find($id){
		if (!$id || !is_numeric($id)) {
			return array();
		}
		return $this->$_db->where("id=$id")->find();
	}

	public function updatePositionById($id,$data){
		if (!$id || !is_numeric($id)) {
			throw_exception('ID非法！');
		}
		if (!$data || !is_array($data)) {
			throw_exception('更新数据非法！');
		}
		$data['update_time'] = time();
		return $this->$_db->where("id=$id")->save($data);
	}

	public function addPosition($positions = array()){
		if (!$positions || !is_array($positions)) {
			return 0;
		}
		$positions['create_time'] = time();
		return $this->$_db->add($positions);
	}

	public function setStatus($data){
		return $this->$_db->save($data);
	}
	
	public function getNormalPositions(){
		return $this->$_db->where('status != -1')->select();
	}

	/**
	 * 获取推荐位下拉栏内容
	 * @author MikeCen <mikecen9@gmail.com>
	 */
	public function getPositionMenus(){
		$data = array(
			'status' => 1,
		);
		$res = $this->$_db->where($data)
		->order('id')
		->select();
		return $res;
	}

	public function countPostion(){
		return $this->$_db->count();
	}

}