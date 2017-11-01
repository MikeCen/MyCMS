<?php 
namespace Admin\Controller;
use Think\Controller;
use Think\Upload;

class ImageController extends CommonController{

    private $_uploadObj = '';
    const ROOT_PATH = './Public/uploads/';
    const PIC_THUMB = 'pic_thumb/';
    const PIC_CONTENT = 'pic_content/';

    public function __construct(){
        $this->_uploadObj = new \Think\Upload();// 实例化上传类
        $this->_uploadObj->maxSize   =     314572800000 ;// 设置附件上传大小
        $this->_uploadObj->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $this->_uploadObj->rootPath  =     self::ROOT_PATH; // 设置附件上传根目录
        $this->_uploadObj->subName   =     array('date','Ym/d');
    }

    public function thumbPicUpload(){
        $this->_uploadObj->savePath = self::PIC_THUMB; // 设置附件上传（子）目录
        $url = self::ROOT_PATH . self::PIC_THUMB;
        //检测目录是否存在，否则递归创建目录
        if (!is_dir($url)) {
            mkdir($url,0777,true);
        }
        // 上传文件
        $info   =   $this->_uploadObj->upload();
        if(!$info){  
            // 上传错误提示错误信息
            $this->error($this->_uploadObj->getError());
        }
        else{ 
            $pic_src = $info['upload_img']['savepath'].$info['upload_img']['savename'];
            $this->ajaxReturn(array("path"=>$pic_src,"status"=>1));
        }
    }

    public function contentPicUpload(){ 
        $this->_uploadObj->savePath = self::PIC_CONTENT; // 设置附件上传（子）目录
        $url = self::ROOT_PATH . self::PIC_CONTENT;
        //检测目录是否存在，否则递归创建目录
        if (!is_dir($url)) {
            mkdir($url,0777,true);
        }
        $res = $this->_uploadObj->upload();
        if($res === false) {
            return showKind(1,'上传失败！');
        }
        return showKind(0, self::ROOT_PATH . $res['imgFile']['savepath'] . $res['imgFile']['savename']);
    }

}