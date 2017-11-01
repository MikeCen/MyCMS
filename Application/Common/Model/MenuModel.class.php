<?php 
namespace Common\Model;
use Think\Model;

class MenuModel extends Model{
	private $_db = '';
	public function __construct(){
		$this->$_db = M('menu');
	}

	public function addMenu( $menu = array() ){
		if (!$menu || !is_array($menu)) {
			return 0;
		}
		return $this->$_db->add($menu);
	}

	/**
	 * 菜单管理分页数据
	 * @author MikeCen <mikecen9@gmail.com>
	 */
	public function getMenus($data,$page,$pageSize=10){
		$data['status'] = array('neq',-1); //NEQ指不等于
		$offset = ( $page - 1 ) * $pageSize;
		$list = $this->$_db->where($data)->order('listorder desc,menu_id desc')->limit($offset,$pageSize)->select();
		return $list;
	}
	
	/**
	 * 菜单管理分页页码
	 * @author MikeCen <mikecen9@gmail.com>
	 */
	public function getMenusCount($data=array()){
		$data['status'] = array('neq',-1);
		return $this->$_db->where($data)->count();
	}

	public function find($id){
		if (!$id || !is_numeric($id)) {
			return array();
		}
		return $this->$_db->where('menu_id='.$id , 'status=1')->find();
	}

	public function updateMenuById($id,$data){
		if (!$id || !is_numeric($id)) {
			throw_exception('ID非法！');
		}
		if (!$data || !is_array($data)) {
			throw_exception('非法更新数据！');
		}
		return $this->$_db->where('menu_id='.$id)->save($data);
	}

	public function setStatusById($id,$status){
		if (!id || !is_numeric($id)) {
			throw_exception('ID非法！');
		}
		if (!status || !is_numeric($status)) {
			throw_exception('状态(Status)数据非法！');
		}
		$data['status'] = $status;
		return $this->$_db->where("menu_id=$id")->save($data);
	}

	public function updateMenuListorderById($menuId,$listorder){
		if (!$menuId || !is_numeric($menuId)) {
			throw_exception('ID非法！');
		}
		$data = array(
			'listorder' => intval($listorder),
		);
		return $this->$_db->where("menu_id=$menuId")->save($data);
	}

	public function getAdminMenus(){
		$data = array(
			'status' => array('neq',-1),
			'type' => 1,
		);
		return $this->$_db->where($data)->order('listorder desc,menu_id desc')->select();
	}

	public function getBarMenus() {
		$data = array(
			'status' => 1,
			'type' => 0,
		);
		$res = $this->$_db->where($data)
		->order('listorder desc,menu_id desc')
		->select();
		return $res;
	}
	
}