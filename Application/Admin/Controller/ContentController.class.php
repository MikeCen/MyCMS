<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

/**
 * 文章内容管理
 */
class ContentController extends CommonController {

    public function index(){
        $condition = array();
        if ($_GET['title']) {
            $condition['title'] = $_GET['title'];
            $this->assign('titleEnter', $condition['title']);
        }else{
            $this->assign('titleEnter','文章标题');
        }
        if ($_GET['catid']) {
            $condition['catid'] = intval($_GET['catid']);
            $this->assign('choose',$condition['catid']);
        }else{
            $this->assign('choose',-1);
        }
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = 5;
        $news = D("News")->getNews($condition,$page,$pageSize);
        $count = D("News")->getNewsCount($condition);

        $res = new \Think\Page($count,$pageSize);
        $pageRes = $res->show();
        //获取推荐位内容
        $positions = D("Position")->getNormalPositions();
        $this->assign('positions',$positions);
        $this->assign('news',$news);
        $this->assign('pageres',$pageRes);

        $this->assign('webSiteMenu',D('Menu')->getBarMenus());
        $this->display();
    }

    public function add(){
        if ($_POST) {
            if (!isset($_POST['title']) || !$_POST['title']) {
                return show(0,'标题不存在！');
            }
            if (!isset($_POST['small_title']) || !$_POST['small_title']) {
                return show(0,'短标题不存在！');
            }
            if (!isset($_POST['content']) || !$_POST['content']) {
                return show(0,'内容不存在！');
            }
            if (!isset($_POST['description']) || !$_POST['description']) {
                return show(0,'描述不存在！');
            }
            if (!isset($_POST['keywords']) || !$_POST['keywords']) {
                return show(0,'关键字不存在！');
            }
            //如存在news_id则跳转到function save进行更新操作
            if ($_POST['news_id']) {
                return $this->save($_POST);
            }
            $_POST['username'] = getLoginUsername();
            $newsId = D("News")->insert($_POST);
            if ($newsId) {
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D("NewsContent")->insert($newsContentData);
                if ($cId) {
                    return show(1,'新增成功！');
                }else{
                    return show(0,'主表新增成功！副表新增失败！');
                }
            }else{
                 return show(0,'新增失败！');
            }
        }else{
            $webSiteMenu = D("Menu")->getBarMenus();
            $titleFontColor = C("TITLE_FONT_COLOR");
            $copyFrom = C("COPY_FROM");
            $this->assign('webSiteMenu',$webSiteMenu);
            $this->assign('titleFontColor',$titleFontColor);
            $this->assign('copyfrom',$copyFrom);
            $this->display();
        }
    }

    public function edit(){
        $newsId = $_GET['id'];
        if (!$newsId) {
            $this->redirect('Content/Index');
        }
        $news = D("News")->find($newsId);
        if (!$news) {
            $this->redirect('Content/Index');   
        }
        $newsContent = D("NewsContent")->find($newsId);
        if ($newsContent) {
            $news['content'] = $newsContent['content'];
        }
        $webSiteMenu = D("Menu")->getBarMenus();
        $this->assign('webSiteMenu',$webSiteMenu);
        $this->assign('titleFontColor',C("TITLE_FONT_COLOR"));
        $this->assign('copyfrom',C("COPY_FROM"));
        $this->assign('news',$news);
        $this->display();
    }

    public function save($data){
        $newsId = $data['news_id'];
        unset($data['news_id']);
        try{
            $nId = D('News')->updateNewsById($newsId,$data);
            if ($nId === false) {
                return show(0,'更新失败！');
            } else {
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D("NewsContent")->updateNewsContentById($newsContentData);
                if ($cId === false) {
                    return show(0,'主表新增成功！副表新增失败！');
                }
                return show(1,'更新成功！');
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }

    public function setStatus(){
        try{
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                $res = D("News")->setStatusById($id,$status);
                if ($res) {
                    return show(1,'修改成功！');
                }else{
                    return show(0,'修改失败！');
                }
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        return show(0,'未提交任何数据！');
    }

    public function listorder(){
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        try{
            foreach($listorder as $newsId => $v){
                $id = D("News")->updateNewsListorderById($newsId,$v);
                if ($id === false) {
                    $errors[] = $newsId;
                }
            }
            if ($errors) {
                return show(0,'排序失败-'.implode(',', $errors),array('jump_url'=>$jumpUrl));
            }
            return show(1,'排序成功！',array('jump_url'=>$jumpUrl));
        }catch (Exception $e){
            return show(0,$e->getMessage());
        }
        return show(0,'排序数据失败！',array('jump_url'=>$jumpUrl));
    }

    public function push(){
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        $newsId = $_POST['push'];
        if (!$newsId || !is_array($newsId)) {
            return show(0,'请选择推荐的文章ID进行推荐！');
        }
        if (!$positionId) {
            return show(0,'没有选择推荐位！');
        }
        try{
            $news = D("News")->getNewsByNewsIdIn($newsId);
            if (!$news ) {
                return show(0,'没有相关内容！');
            }
            foreach ($news as $new) {
                $data = array(
                    'position_id' => $positionId,
                    'title' => $new['title'],
                    'thumb' => $new['thumb'],
                    'news_id' => $new['news_id'],
                    'status' => 1,
                    'create_time' =>$new['create_time'],
                    );
                $position = D('PositionContent')->insert($data);
                if (!$position ) {
                    return show(0,'insert失败！');
                }
            }
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
        return show(1,'推荐成功！',array('jump_url'=>$jumpUrl));
    }
}