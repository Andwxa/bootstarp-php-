<?php
/*
 * @Date: 2022-10-24 11:27:30
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-10-28 12:07:20
 * Github:https://github.com/Andwxa
 * @Description: 举报审核
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
$webpage = 'process_reportView';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true,true);
// 获得类型和id
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($type == 'userinfo') {
    $typeName = '用户信息';
}elseif($type == 'article'){
    $typeName = '文章信息';
}elseif ($type == 'comment') {
    $typeName = '评论信息';
}else{
    $_SESSION['showInformation'] = ['./reportManagement.php','3','error','没有收到传递的信息，请联系管理人员！'];
    die(header("Refresh:0;url=../showInformation.php"));
}
// 举报成功
if ($_POST['success']) {
    $mysqls_interface->updateInforOfCompletemMysql($type,$id,2);
    $_SESSION['showInformation'] = ['./reportManagement.php','1','success','成功'];
    // 系统日志
    $mysqls_interface->addJournalMysql("管理员确认 $typeName 类型编号为 $id 举报有效，将会对其进行惩戒",$uId);
    // 系统消息
    $mcId = $mysqls_interface->addMessageContentMysql("管理员确认 $typeName 类型编号为 $id 举报有效");
    $mysqls_interface->addMessageMysql('only',$uId,$mcId);
}elseif($_POST['failure']){
    $mysqls_interface->updateInforOfCompletemMysql($type,$id,3);
    $_SESSION['showInformation'] = ['./reportManagement.php','1','success','驳回'];
    // 系统日志
    $mysqls_interface->addJournalMysql("管理员确认 $typeName 类型编号为 $id 举报无效",$uId);
    // 系统消息
    $mcId = $mysqls_interface->addMessageContentMysql("管理员确认 $typeName 类型编号为 $id 举报无效");
    $mysqls_interface->addMessageMysql('only',$uId,$mcId);
}else {
    $_SESSION['showInformation'] = ['./reportView.php','3','error','没有传入数据'];
}
die(header("Refresh:0;url=../showInformation.php"));
?>