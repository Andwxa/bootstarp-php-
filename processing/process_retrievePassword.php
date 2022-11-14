<?php
/*
 * @Date: 2022-10-19 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:59:08
 * Github:https://github.com/Andwxa
 * @Description: 修改密码处理
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
$webpage = 'process_retrievePassword';
// 开启session
session_start();
// 当没有表单提交时退出程序
if(empty($_POST)){
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','没有表单提交!'];
    die(header("Refresh:0;url=../showInformation.php"));
}
// 判断表单中各字段是否都已填写
$chcek_fields = array('findUsername','newPassword','findEmail');
foreach($chcek_fields as $v){
	if(empty($_POST[$v])){
		$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error',"错误：'.$v.'字段不能为空!三秒后自动返回"];
		die(header("Refresh:0;url=../showInformation.php"));
	}
}
// 接收需要处理的表单字段
$username = $_POST['findUsername'];
$password = $_POST['newPassword'];
$email = $_POST['findEmail'];
// 获取用户输入的验证码字符串
$code = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';
// 判断SESSION中是否存在验证码
if(empty($_SESSION['captcha_code'])){
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','验证码已过期，请重新登录'];
    die(header("Refresh:0;url=../showInformation.php"));
}
// 将验证码转成小写然后再进行比较
if (strtolower($code) == strtolower($_SESSION['captcha_code'])){
	if ($username || $password || $email) {
		$data = array(
			'username' => $username,
			'password' => $password,
			'email' => $email,
		);
		$validate = array(
			'email' => 'checkEmail',
			'username' => 'checkAccount',
			'password' => 'checkPassword',
			
		);
		$error = array();
		foreach($validate as $k=>$v){
			// 运用可变函数，实现不同字段调用不同函数
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
		// 判断账号是否存在
		$uId = $mysqls_interface->selectUserByNameExistMysql($username);
		if(!$uId){
			$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','账号不存在！'];
			die(header("Refresh:0;url=../showInformation.php"));
		}else {
			// 账号和邮箱是否一致
			$rst = $mysqls_interface->checkUserNameEmailMatches($username,$email);
			if($rst){
				// 修改
				$rst = $mysqls_interface->updateUserPasswordMysql($username,$password);
				if($rst){
					$_SESSION['showInformation'] = ['./loginAndRegister.html','2','success','修改成功！'];
					// 系统日志
					$mysqls_interface->addJournalMysql('用户修改密码',$uId);
					// 系统消息
					$mcId = $mysqls_interface->addMessageContentMysql("您完成了修改密码");
					$mysqls_interface->addMessageMysql('only',$uId,$mcId);
					die(header("Refresh:0;url=../showInformation.php"));
				}else{
					$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','执行失败，请联系管理人员！'];
					die(header("Refresh:0;url=../showInformation.php"));
				}
			}else{
				$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','账户和邮箱不一致！'];
				die(header("Refresh:0;url=../showInformation.php"));
			}
		}
	}else{
		foreach($error as $val){
			$check = $check.$val.' ';
		}
		$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error',$check];
		die(header("Refresh:0;url=../showInformation.php"));
	}
} else{
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','输入的验证码是:'.strtolower($code).'系统的验证码是:'.strtolower($_SESSION['captcha_code'])];
	die(header("Refresh:0;url=../showInformation.php"));
}