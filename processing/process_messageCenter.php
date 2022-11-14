<?php
/*
 * @Date: 2022-10-13 15:07:40
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:57:29
 * Github:https://github.com/Andwxa
 * @Description: 信息中心处理
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
$webpage = 'process_messageCenter';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得类型和id和内容
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id) {
    if ($_POST['read']) {
        $rs = $mysqls_interface->updateMessageMysql($id);
        if ($rs) {
            $_SESSION['showInformation'] = ['./messageCenter.php','0','success','已读'];
        }else{
            $_SESSION['showInformation'] = ['./messageCenter.php','3','error','数据库出现问题，请联系管理人员！'];
        }
    }
}else{
    $rs = $mysqls_interface->updateMessageMysql('',$uId);
    if ($rs) {
        $_SESSION['showInformation'] = ['./messageCenter.php','0','success','已读'];
    }else{
        $_SESSION['showInformation'] = ['./messageCenter.php','3','error','数据库出现问题，请联系管理人员！'];
    }
}
die(header("Refresh:0;url=../showInformation.php"));
?>