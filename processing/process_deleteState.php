<?php
/*
 * @Date: 2022-10-03 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:55:56
 * Github:https://github.com/Andwxa
 * @Description: 注销用户处理
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入数据库
require('../deploy/after_end.php');
// 设定字符集
header('Content-Type:text/html;charset=utf-8');
// 网页的名称
$webpage = 'process_deleteState';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得用户并过期
setcookie("funLoginCookie", $_COOKIE['funLoginCookie'], time()-3600,'/');
$mysqls_interface->deleIdentityMysql($_COOKIE['funLoginCookie'],'');
// 判断
if ($_COOKIE['funLoginCookie']){
	$_SESSION['showInformation'] = ['./index.php','1','success','注销用户成功！'];
	// 系统日志
	$mysqls_interface->addJournalMysql("注销用户",$uId);
}else
	$_SESSION['showInformation'] = ['./index.php','3','error','未能注销用户！请联系管理人员！'];
die(header("Refresh:0;url=../showInformation.php"));
?>