<?php
/*
 * @Date: 2022-10-02 19:36:07
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:59:53
 * Github:https://github.com/Andwxa
 * @Description: 用户控制处理
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入数据库
require('../deploy/after_end.php');
// 设定字符集
header('Content-Type:text/html;charset=utf-8');
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 网页的名称
$webpage = 'process_userManagement';
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true,true);
$id = $_POST['id'];
// 判断用户身份
if ($_POST['dele'])  {
    $rs = $mysqls_interface->deleUserMysql($id);
    if ($rs){ 
        $rs = $mysqls_interface->deleUserInformationMysql($id);
        if ($rs) {
            $_SESSION['showInformation'] = ['./userManagement.php','1','success','用户删除成功！'];
            $mysqls_interface->addJournalMysql('管理员删除用户',$uId);
            // 系统日志
            $mysqls_interface->addJournalMysql("删除了id为 $id 的用户",$uId);
            // 系统消息
            $mcId = $mysqls_interface->addMessageContentMysql("您删除了id为 $id 的用户");
            $mysqls_interface->addMessageMysql('only',$uId,$mcId);
        }else
            $_SESSION['showInformation'] = ['./userManagement.php','3','error','用户删除成功，用户详细信息删除失败，请联系管理人员！'];
    }else
        $_SESSION['showInformation'] = ['./userManagement.php','3','error','用户删除失败，请联系管理人员！'];
}elseif($_POST['seal']){
    // 获得时间
    $day =  $_POST['day'];
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];
    if (!is_numeric($day)) {$day=0;}
    if (!is_numeric($hour)) {$hour=0;}
    if (!is_numeric($minute)) {$minute=0;}
    $timeAll = $day*24*60*60+$hour*60*60+$minute*60;
    if ($timeAll != 0) {
        $time = time()+$timeAll;
        $rs = $mysqls_interface->updateUserEmployTimeMysql($id,$time);
        if ($rs) {
            $_SESSION['showInformation'] = ['./userManagement.php','1','success','用户封印成功！'];
            // 系统日志
            $mysqls_interface->addJournalMysql("封印id为 $id 的用户，封印时间 $timeAll 分钟",$uId);
        }else
            $_SESSION['showInformation'] = ['./userManagement.php','3','error','用户封印失败，请联系管理人员！'];
    }else{
        $_SESSION['showInformation'] = ['./userManagement.php','3','error','用户封印失败，需要封印时间，请联系管理人员！'];
    }
}else
    $_SESSION['showInformation'] = ['./userManagement.php','3','error','传入数据失败，请联系管理人员！'];
die(header("Refresh:0;url=../showInformation.php"));
?>