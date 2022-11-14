<?php
/*
 * @Date: 2022-10-15 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:58:14
 * Github:https://github.com/Andwxa
 * @Description: 发布文章处理
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
$webpage = 'process_publishArticle';
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 设定字符集
header('Content-Type:text/html;charset=utf-8');
// 当没有表单提交时退出程序
if (empty($_POST)) {
    die('没有表单提交，退出');
}
$cId = $_POST['culture'];
$title = $_POST['title'];
$content = $_POST['content'];
// 检查文章标题是否正确
$rs = $mysqls_interface->selectColumnByIdOrNameMysql($cId);
if ($rs) {
    // 添加文章
    $rs = $mysqls_interface->addReviewArticleMysql($cId,$uId,$title,$content);
    if ($rs){
        $_SESSION['showInformation'] = ['./publish.php','1','success','发布成功，等待管理员审核'];
        // 系统日志
        $mysqls_interface->addJournalMysql("发表标题为《$title 》文章",$uId);
        // 系统消息
        $mcId = $mysqls_interface->addMessageContentMysql("您发表了一篇标题为《$title 》的文章现在进入管理员审核名单");
        $mysqls_interface->addMessageMysql('only',$uId,$mcId);
    }else
        $_SESSION['showInformation'] = ['./publish.php','3','error','发布失败'];
}else
    $_SESSION['showInformation'] = ['./publish.php','3','error','文章标题与数据库匹配不上！'];

die(header("Refresh:0;url=../showInformation.php"));
?>