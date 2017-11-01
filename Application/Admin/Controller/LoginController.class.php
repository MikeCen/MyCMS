<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class LoginController extends Controller {
    
    public function index(){
        if (!session('?adminUser')) {
             $this->display();
        }else{
            // $this->success('您已经登陆过了！','/index.php?m=admin&c=index');  //方法1  *2种提示方法可选
            $this->redirect('Index/index');  //方法2    *2种提示方法可选
        }
    }

    public function check() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (!trim($username)) {
        	return show(0,'用户名不能为空！');
        }
        if (!trim($password)) {
        	return show(0,'密码不能为空！');
        }
        $ret =  D('Admin')->getAdminByUserName($username);
        if (!$ret || $ret['status'] != 1) {
            return show(0,'用户名不存在！');
        }
        if ($ret[password] != getMd5Password($password)) {
            return show(0,'密码错误！');
        }
        session('adminUser',$ret);
        //写入最后登录时间
        M("Admin")->save(array('admin_id'=>$ret['admin_id'],'lastlogintime'=>time())); 
        return show(1,'登陆成功！');
    }

    public function loginout(){
        session('adminUser',null);
        $this->success('退出账户成功！','/index.php?m=admin&c=login&a=index');  //方法1  *2种提示方法可选
        // $this->redirect('Login/index');  //方法2    *2种提示方法可选
    }
}