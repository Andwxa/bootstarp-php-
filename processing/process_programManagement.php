<?php
/*
 * @Date: 2022-10-01 21:01:46
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:58:02
 * Github:https://github.com/Andwxa
 * @Description: 栏目处理
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
$webpage = 'process_programManagement';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true,true);
$id = $_POST['id'];
$name = $_POST['name'];
$sort = $_POST['sort'];
// 更新
if ($_POST['update']){
    if (!is_numeric($sort) or strlen($sort) > 10) {
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','显示顺序必须是数值型，并且长度必须小于10'];
        die(header("Refresh:0;url=../showInformation.php"));
    }
    if (strlen($name) > 12) {
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','栏目名称长度必须小于12'];
        die(header("Refresh:0;url=../showInformation.php"));
    }
    if (!is_numeric($id) or strlen($id) > 10) {
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','id必须是数值型，并且长度必须小于10'];
        die(header("Refresh:0;url=../showInformation.php"));
    }
    $rs = $mysqls_interface->updateColumnMysql($id,$name,$sort);
    if ($rs) {
        $_SESSION['showInformation'] = ['./programManagement.php','1','success','更新成功'];
        $mysqls_interface->addJournalMysql('更新栏目数据',$uId);
    }else{
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','更新失败，请联系管理人员'];
    }
    die(header("Refresh:0;url=../showInformation.php"));
}elseif ($_POST['add']){
    if (!is_numeric($sort) or strlen($sort) > 10) {
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','显示顺序必须是数值型，并且长度必须小于10'];
        die(header("Refresh:0;url=../showInformation.php"));
    }
    if (strlen($name) > 12) {
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','栏目名称长度必须小于12'];
        die(header("Refresh:0;url=../showInformation.php"));
    }
    $rs = $mysqls_interface->addColumnMysql($name,$sort);
    if ($rs){
        $_SESSION['showInformation'] = ['./programManagement.php','1','success','添加栏目成功'];
        // 系统日志
        $mysqls_interface->addJournalMysql("添加名称《$name 》栏目",$uId);
    }else{
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','添加栏目失败，请联系管理人员'];
    }
    die(header("Refresh:0;url=../showInformation.php"));
}elseif ($_POST['dele']){
    $rs = $mysqls_interface->deleColumnByIdMysql($id);
    if ($rs){
        $_SESSION['showInformation'] = ['./programManagement.php','1','success','删除栏目成功'];
        // 系统日志
        $mysqls_interface->addJournalMysql("删除名称《$name 》栏目",$uId);
    }else{
        $_SESSION['showInformation'] = ['./programManagement.php','3','error','删除栏目失败，请联系管理人员'];
    }
    die(header("Refresh:0;url=../showInformation.php"));
}else{
    $_SESSION['showInformation'] = ['./programManagement.php','3','error','非法进入'];  
}
die(header("Refresh:0;url=../showInformation.php"));
?>