<?php 
namespace Home\Controller;
use Think\Controller;
class CatController extends CommonController{
	public function index(){
		$id = $_GET['id'];
		if (!is_numeric($id)) {
			return $this->error('ID不存在');
		}
		$cId = intval($id);
		$nav = D("Menu")->find($cId);
		if (!$nav) {
			return $this->error('栏目ID不存在或为非正常状态');
		}
		//获取首页右侧排行列表
		$rankNews = M("news")->select(array('status'=>1,'order'=>'count desc,news_id desc' ,'limit'=>10));
		//获取首页<右侧广告位>
		$advNews = M("position_content")
                                            ->where(array('status'=>1,'position_id'=>3))
                                           ->select(array('limit'=>2));
		//获取条件
		$condition = array('catid' => $cId);
		//分页
		$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
		$pageSize = 5; 	//每页显示的条目数
		$news = D("News")->getNews($condition,$page,$pageSize);
		$count = D("News")->getNewsCount($condition);
		$res = new \Think\Page($count,$pageSize);
		$pageRes = $res->show();
		$this->assign('result',array(
			'rankNews' => $rankNews,
			'advNews' => $advNews,
			'catId' => $id,
			'listNews' => $listNews,
			'pageRes' => $pageRes,
			'listNews' => $news,
		));
		$this->display();
	}
}