<?php 
namespace Home\Controller;
use Think\Controller;
class DetailController extends CommonController{
	public function index(){
		$id = $_GET['id'];
		if (!is_numeric($id)) {
			return $this->error('文章ID非法');
		}
		$intId = intval($id);
		$news = M("news")->where("news_id = $intId and status = 1")->find();
		if (!$news) {
			return $this->error('文章不存在');
		}
		$plusCount = ++$news['count'];
		D("News")->updateCount($intId,$plusCount);	//更新文章阅读数
		//获取首页右侧排行列表
		$rankNews = M("news")->select(array('status'=>1,'order'=>'count desc,news_id desc' ,'limit'=>10));
		//获取首页<右侧广告位>
		$advNews = M("position_content")
                                            ->where(array('status'=>1,'position_id'=>3))
                                           ->select(array('limit'=>2));
		$newsContent = M("news_content")->where("news_id = $intId")->find();
		$decodeContent = htmlspecialchars_decode($newsContent['content']);
		$this->assign('news',$news);
		$this->assign('newsContent',$decodeContent);
		$this->assign('result',$news['catid']);
		$this->assign('result',array(
			'rankNews' => $rankNews,
			'advNews' => $advNews,
			'catId' =>999,
		));
		$this->display("Detail/index");
	}

	public function view(){
		if (!session('?adminUser')) {
			return $this->error('越权访问！');
		}
		$this->index();
	}
}