<?php 
namespace Admin\Controller;
use Think\Controller;

class CronController extends CommonController {
	/**
	 * 备份数据库
	 * @author MikeCen <mikecen9@gmail.com>
	 */
	public function dumpMysql(){
		//默认备份路径  .\Application\Runtime\Data
		$dir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'Application'.DIRECTORY_SEPARATOR. 'Runtime' . DIRECTORY_SEPARATOR .'Data' .DIRECTORY_SEPARATOR;
		$shell = 'mysqldump -u'.C("DB_USER").' '.C("DB_NAME").' > ' . $dir.'databaseBackup-'.date('Ymd-His').'.sql';
		exec($shell);
		return show(1,'数据库备份成功！');
	}

	 /**
	 * 自动备份数据库（通过配合Crontab定时任务）
	 * @author MikeCen <mikecen9@gmail.com>
	 */
	 public function dumpMysqlAuto(){
	 	if (APP_CRONTAB != 1) {
	 		die("the_file_must_exec_crontab");
	 	}
	 	$result = D("Basic")->select();
	 	if (!$result['dumpmysql']) {
	 		die('系统没有设置自动生成备份数据库');
	 	}
	 	$this->dumpMysql();
	 }
}