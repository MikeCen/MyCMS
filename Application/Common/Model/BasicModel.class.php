<?php 
namespace Common\Model;
use Think\Model;

class BasicModel extends Model{

	public function __construct(){

	}

	public function save($data = array()){
		if (!$data) {
			throw_exception('没有提交数据！');
		}
		$id = F('basic_web_config',$data);
		return $id;
	}

	public function select(){
		return F('basic_web_config');
	}
}