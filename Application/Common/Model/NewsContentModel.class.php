<?php
namespace Common\Model;
use Think\Model;

/**
 * 文章内容content model操作
 * @author  MikeCen <mikecen9@gmail.com>
 */
class NewsContentModel extends Model {
    private $_db = '';

    public function __construct() {
        $this->$_db = M('news_content');
    }

    public function insert($data=array()){
        if (!$data || !is_array($data)) {
            return 0;
        }
        $data['create_time'] = time();
        if (isset($data['content']) && $data['content']) {
            $data['content'] = htmlspecialchars($data['content']);
        }
        return $this->$_db->add($data);
    }

    public function find($id){
        if ($id && is_numeric($id)) {
           return $this->$_db->where('news_id=' . $id )->find();
       }
   }

   public function updateNewsContentById($data=array()){
    if (!$data || !is_array($data)) {
        return 0;
    }
    $data['update_time'] = time();
    if (isset($data['content']) && $data['content']) {
        $data['content'] =htmlspecialchars($data['content']);
    }
    return $this->$_db->where('news_id='.$data['news_id'])->save($data);
}

}
