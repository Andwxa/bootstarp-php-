<?php
/*
 * @Date: 2022-10-03 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:57:19
 * Github:https://github.com/Andwxa
 * @Description: 登录处理
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入表单验证函数库
require('../utility/check_form.php');
// 引入数据库
require('../deploy/after_end.php');
// 设定字符集
header('Content-Type:text/html;charset=utf-8');
// 网页的名称
$webpage = 'process_login';
// 当没有表单提交时退出程序
if(empty($_POST)){
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','没有表单提交！'];
	die(header("Refresh:0;url=../showInformation.php"));
}

// 判断表单中各字段是否都已填写
$chcek_fields = array('name','pwd','captcha');
foreach($chcek_fields as $v){
	if(empty($_POST[$v])){
		$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error',"错误：'.$v.'不能为空!"];
		die(header("Refresh:0;url=../showInformation.php"));
	}
}
// 接收需要处理的表单字段
$username = $_POST['name'];
$password = $_POST['pwd'];
// 获取用户输入的验证码字符串
$code = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';
// 判断SESSION中是否存在验证码
if(empty($_SESSION['captcha_code'])){
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','验证码已过期，请重新'];
	die(header("Refresh:0;url=../showInformation.php"));
}
// 将验证码转成小写然后再进行比较
if (strtolower($code) == strtolower($_SESSION['captcha_code'])){
	//匹配
	if ($username || $password) {
		$data = array(
			'username' => $username,
			'password' => $password,
		);
		$validate = array(
			'username' => 'checkAccount',
			'password' => 'checkPassword',
		);
		$error = array();
		foreach($validate as $k=>$v){
			//运用可变函数，实现不同字段调用不同函数
			$result = $v($data[$k]);
			if($result !== true){
				$error[] = $result;
			}
		}
	}
	if(empty($error)){
		// 连接数据库
		$mysqls_interface = new mysqls();
		$mysqls_interface->linkMysql();
		// 判断账户是否存在
		$uId = $mysqls_interface->selectUserByNameExistMysql($username);
		if(!$uId)
		{
			$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','账户或密码错误！'];
		}else {
			// 判断账户和密码是否一致
			$rst = $mysqls_interface->checkUserNamePasswordMatches($username,$password);
			if($rst){
				// 根据账号获得uId
				$uId = $mysqls_interface->selectUserByNameExistMysql($username);
				// 获得登录是否要保持7天
				$loginType = $_POST['loginType'];
				// 获取时间
				$time = time();
				// 防止多次登录，用户cookie出现第二次就删除上一个cookie
				if ($mysqls_interface->issetIdentityUIdMysql($uId))
					$delSessionName = $mysqls_interface->deleIdentityMysql('',$uId);
				// 如果session重复就重新获取
				do {
					// 生成随机数
					$randomNumber = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
					$randStr = str_shuffle($randomNumber);
					$code = substr($randStr, 0, 32);
				} while (!$mysqls_interface->issetIdentityByKeyMysql($code));
				// 添加cookie,保存7天登录,否则一小时记录
				if ($loginType == 'loginType') {
					$time = time()+3600*24*7;
					setcookie("funLoginCookie", $code, time()+3600*24*7,'/');
				}else{
					$time = time()+3600;
					setcookie("funLoginCookie", $code, time()+3600,'/');
				}
				// 数据库添加session
				$mysqls_interface->addIdentityMysql($code,$time,$uId);
				$_SESSION['showInformation'] = ['./index.php','1','success','登录成功！'];
				// 获得昵称
				$nickname = $mysqls_interface->selectUSerInformationAcquireNicknameById($uId);
				// 系统日志
				$mysqls_interface->addJournalMysql("$nickname 登录成功",$uId);
			}else{
				//echo '账户和密码不一致';
				$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','账户或密码错误！'];
			}
		}
	}else{
		foreach($error as $val){
			$check = $check.$val.' ';
		}
		$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error',$check];
	}
}else{
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','输入的验证码是:'.strtolower($code).'系统的验证码是:'.strtolower($_SESSION['captcha_code'])];
}
unset($_SESSION['captcha_code']); //清除SESSION数据
die(header("Refresh:0;url=../showInformation.php"));
?>
