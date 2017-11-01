## MyCMS

### A CMS based on the ThinkPHP framework. | 一个基于ThinkPHP框架的CMS.
### @author Mike Cen [mikecen9@gmail.com]

### 1、简介 
MyCMS是一个内容管理系统（CMS），更是一个致力于让开发工作流变得简单的web平台。

### 2、DEMO网站 
前台：http://www.sing-news.tk/<br />
后台：https://www.sing-news.tk/admin.php <br />
*DEMO后台管理账户默认用户名/密码均为admin*

### 3、GitHub https://github.com/MikeCen/MyCMS

### 4、基础库 
本CMS基于ThinkPHP开发

### 5、安装 最小系统要求 
•	PHP 5.4+<br />
•	MySQL 5.5+<br />
•	Apache / Nginx / Caddy <br />

### 6、安装步骤
在服务器上准备一个空的目录；<br />
下载安装文件；<br />
解压下载的安装文件到准备好的空目录；<br />
分配写权限到/Application/Runtime及其所有子目录/文件，且为更目录下的index.html分配写权限（首页静态化）；<br />
打开文件Application/Common/Conf/db.php，修改其中的数据库登陆参数；<br />
在浏览器地址栏输入http://域名/admin.php并回车进入后台；<br />
按照安装步骤说明一步一步执行安装操作 安装过程中可能出现的问题：<br />
 •	下载应用文件显示500错误：需要增加或取消web服务器的超时时间限制。例如，Apache的FastCGI有时候有一个被设置为30s的-idle-timeout选项 <br />
 •	打开应用的时候显示空白页面：检查当前文件及文件夹的权限设置。例如，运行chmod -R 777 命令往往能解决这个问题 <br />
 •	MySQL显示错误“Syntax error or access violation: 1067 Invalid default value for …”：检查MySQL设置文件确保取消了NO_ZERO_DATE设置<br />
