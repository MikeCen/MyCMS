<?php
namespace Common\Model;
use Think\Model;

/**
 * 文章内容model操作
 * @author  MikeCen <mikecen9@gmail.com>
 */
class NewsModel extends Model {
    private $_db = '';

    public function __construct() {
        $this->$_db = M('news');
    }

        /**
         * 文章管理分页数据
         * @author MikeCen <mikecen9@gmail.com>
         */
        public function getNews($data,$page,$pageSize=10){
            $conditions = $data;
            if (isset($data['title']) && $data['title']) {
                $conditions['title'] = array('like','%'.$data['title'].'%');
            }
            if (isset($data['catid']) && $data['catid']) {
                $conditions['catid'] = intval($data['catid']);
            }
            // $conditions['status'] = array('neq',-1);  //过滤status为-1的条目（即删除的条目）
            $offset = ($page - 1) * $pageSize;
            $list = $this->$_db->where($conditions)
            ->order('create_time desc')
            ->limit($offset,$pageSize)
            ->select();
            return $list;
        }

        /**
         *  文章管理分页页码
         * @author MikeCen <mikecen9@gmail.com>
         */
        public function getNewsCount($data=array()){
            $conditions = $data;
            if (isset($data['title']) && $data['title']) {
                $conditions['title'] = array('like','%'.$data['title'].'%');
            }
            if (isset($data['catid']) && $data['catid']) {
                $conditions['catid'] = intval($data['catid']);
            }
            // $conditions['status'] = array('neq',-1);  //过滤status为-1的条目（即删除的条目）
            return $this->$_db->where($conditions)->count();
        }

        public function find($id){
            if ($id && is_numeric($id)) {
             return $this->$_db->where('news_id=' . $id )->find();
         }
     }

     public function insert($data){
        if (!$data || !is_array($data)) {
            throw_exception('非法数据！');
        }
        $data['create_time'] = time();
        return $this->$_db->add($data);
    }

    public function updateNewsById($id,$data){
        if (!$id || !is_numeric($id)) {
           throw_exception('ID非法！');
       }
       if (!$data || !is_array($data)) {
        throw_exception('非法更新数据！');
    }
    $data['update_time'] = time();
    return $this->$_db->where('news_id='.$id)->save($data);
}

public function setStatusById($id,$status){
    if (!$id || !is_numeric($id)) {
        throw_exception('ID非法！');
    }
    if (!status || !is_numeric($status)) {
        throw_exception('状态数据非法！');
    }
    $data['status'] = $status;
    return $this->$_db->where('news_id='.$id)->save($data);
}

public function updateNewsListorderById($id,$listorder){
    if (!$id || !is_numeric($id)) {
        throw_exception('ID非法！');
    }
    $data = array('listorder'=>intval($listorder));
    return $this->$_db->where('news_id='.$id)->save($data);
}

public function getNewsByNewsIdIn($newIds){
    if (!is_array($newIds)) {
        throw_exception("参数非法！");
    }
    $data = array('news_id' => array('in',implode(',', $newIds)));
    return $this->$_db->where($data)->select();
}

    /**
     * 阅读数计数器
     * @author MikeCen <mikecen9@gmail.com>
     */
    public function updateCount($id,$count){
        if (!is_numeric($id)) {
            throw_exception('ID非法！');
        }
        if (!is_numeric($count)) {
            throw_exception('计数器值非法！');
        }
        $data['count'] = $count;
        return $this->$_db->where("news_id=$id")->save($data);
    }

    /**
     * 获取阅读数最大的文章
     * @author MikeCen <mikecen9@gmail.com>
     */
    public function maxReadAmount(){
        return $this->$_db->order('count desc')->find();
    }

}
