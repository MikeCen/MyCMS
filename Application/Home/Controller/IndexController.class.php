<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {

	public function index($type=''){
                     //获取<首页大图>数据
                      $topPicNews = M("position_content")
                                                  ->join('cms_news ON  cms_position_content.news_id = cms_news.news_id')
                                                  ->where(array(C('DB_PREFIX').'position_content.status'=>1,'position_id'=>1))
                                                  ->select(array('order'=>C('DB_PREFIX').'position_content.create_time desc','limit'=>1));
                    //获取首页三<小图推荐>数据
                      $topSmallNews = M("position_content")
                                            ->where(array('status'=>1,'position_id'=>2))
                                            ->select(array('limit'=>3));
                     //获取首页新闻列表
                      $listNews = M("news")->select(array('status'=>1,'order'=>'update_time desc,create_time desc','limit'=>6));
                    //获取首页右侧排行列表
                      $rankNews = M("news")->select(array('status'=>1,'order'=>'count desc,news_id desc' ,'limit'=>10));
                    //获取首页<右侧广告位>
                      $advNews = M("position_content")
                                            ->where(array('status'=>1,'position_id'=>3))
                                           ->select(array('limit'=>2));
                      $this->assign('result',array(
                               'topPicNews' => $topPicNews,
                               'topSmallNews' => $topSmallNews,
                               'listNews' => $listNews,
                               'rankNews' => $rankNews,
                               'advNews' => $advNews,
                               'topPicCount' => $topPicNews[0]['count'],
                               'catId' =>0,
                        ));
            		/**
            		 * 生成静态化页面
            		 */
            		if ($type === 'buildHtml') {
            			$this->buildHtml('index',HTML_PATH,'Index/index');
            		}else{
            			$this->display();
            		}
	 }

                 /**
                 * 手动生成静态化页面（通过后台页面）
                 * @author MikeCen <mikecen9@gmail.com>
                 */
                 public function build_html(){
                    $this->index('buildHtml');
                    return show(1,'首页缓存生成成功！');
                }

                /**
                 * 自动生成静态化页面（通过配合Crontab定时任务）
                 * @author MikeCen <mikecen9@gmail.com>
                 */
                public function crontab_build_html(){
                    if (APP_CRONTAB != 1) {
                        die("the_file_must_exec_crontab");
                    }
                      $result = D("Basic")->select();
                      if (!$result['cacheindex']) {
                          die('系统没有设置自动生成首页缓存');
                      }
                    $this->index('buildHtml');
              }

                /**
                 * 静态页面中获取动态阅读数  
                 * 需配合Public/js/count.js
                 * @author MikeCen <mikecen9@gmail.com>
                 */
                public function getCount(){
                   if(!$_POST){
                      return show(0,'未接收到POST内容！');
                  }
                  $newsIds = array_unique($_POST);
                  try{
                      $list = D("News")->getNewsByNewsIdIn($newsIds);
                  }catch(Exception $e){
                      return show(0,$e->getMessage());
                  }
                  if (!$list) {
                      return show(0,'没有数据！');
                  }
                  $data = array();
                  foreach ($list as $key => $value) {
                      $data[$value['news_id']] = $value['count'];
                  }
                  return show(1,'success',$data);
              }
          }