<?php
/*
 * @Date: 2022-10-20 16:19:32
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:49:14
 * Github:https://github.com/Andwxa
 * @Description: 发表公告处理
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
$webpage = 'process_bulletinManagement';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true,true);
// 判断对什么类型 个人 组 全体
$identity = $_POST['identity'];
if ($identity == 'only') {
    $userId = $_POST['userId'];
}else
    $userId = '0';
// 公告内容
$bulletin = $_POST['bulletin'];
$content = $_POST['content'];
if ($bulletin == '0' and $content == '') {
    $_SESSION['showInformation'] = ['./bulletinManagement.php','3','error','公告内容不见了，请联系管理人员！'];
    die(header("Refresh:0;url=../showInformation.php"));
}
if ($identity) {
    if ($content) {
        // 创建新的内容
        $mcId = $mysqls_interface->addMessageContentMysql($content);
        $rs = $mysqls_interface->addMessageMysql($identity,$userId,$mcId);
    }else{
        $rs = $mysqls_interface->addMessageMysql($identity,$userId,$bulletin);
    }
    if ($rs) {
        // 系统日志
        $mysqls_interface->addJournalMysql('发布公告',$uId);
        $_SESSION['showInformation'] = ['./bulletinManagement.php','1','success','公告发布成功'];
    }else
        $_SESSION['showInformation'] = ['./bulletinManagement.php','3','error','公告发布失败，请联系管理人员！'];   
}else
    $_SESSION['showInformation'] = ['./bulletinManagement.php','3','error','没有选择对象，请联系管理人员！'];      
die(header("Refresh:0;url=../showInformation.php"));
?>