<?php
/*
 * @Date: 2022-10-19 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:58:41
 * Github:https://github.com/Andwxa
 * @Description: 用户注册处理
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
$webpage = 'process_register';
// 当没有表单提交时退出程序
if(empty($_POST)){
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','没有表单提交!'];
	die(header("Refresh:0;url=../showInformation.php"));
}
// 判断表单中各字段是否都已填写
$chcek_fields = array('username','pw2','email','captcha');
foreach($chcek_fields as $v){
	if(empty($_POST[$v])){
		$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error',"$v.'字段不能为空!"];
		die(header("Refresh:0;url=../showInformation.php"));
	}
}
// 接收需要处理的表单字段
$username = $_POST['username'];
$password = $_POST['pw2'];
$email = $_POST['email'];
// 获取用户输入的验证码字符串
$code = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';
// 判断SESSION中是否存在验证码
if(empty($_SESSION['captcha_code'])){
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','验证码已过期，请重新登录'];
	die(header("Refresh:0;url=../showInformation.php"));
}
// 将验证码转成小写然后再进行比较
if (strtolower($code) == strtolower($_SESSION['captcha_code'])){
	//匹配
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
	// 数据库操作
	if(empty($error)){
		//连接数据库
		$mysqls_interface = new mysqls();
		$mysqls_interface->linkMysql();
		// 判断用户名是否存在
		$rst = $mysqls_interface->selectUserByNameExistMysql($username);
		if($rst)
		{
			$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','账号已经存在，请换个账号'];
			die(header("Refresh:0;url=../showInformation.php"));
		}
		// 增加账户信息
		$rst = $mysqls_interface->addUserMysql($username,$password,$email);
		if($rst){
			// 获得用户表的uId
			$id = $mysqls_interface->selectUserByNameMysql($username);
			$id = $id ->fetch_array();
			$id = $id['uId'];
			// 增加用户详情信息
			$rst = $mysqls_interface->addUserInformationMysql($id);
			if ($rst) {
				$_SESSION['showInformation'] = ['./loginAndRegister.html','1','success','注册成功'];
				// 系统日志
				$mysqls_interface->addJournalMysql('用户注册',$id);
			}else{
				// 失败则删除用户表的
				$mysqls_interface->deleUserMysql($id);
				$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','注册用户详细信息失败，请联系管理人员！'];
			}
		}else{
			$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','注册失败，请联系管理人员！'];;
		}
	}else{
		foreach($error as $val){
			$check = $check.$val.' ';
		}
		$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error',$check];
	}
} else{
	$_SESSION['showInformation'] = ['./loginAndRegister.html','3','error','输入的验证码是:'.strtolower($code).'系统的验证码是:'.strtolower($_SESSION['captcha_code'])];
}
die(header("Refresh:0;url=../showInformation.php"));
?>