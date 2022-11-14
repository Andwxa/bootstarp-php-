<?php
/*
 * @Date: 2022-10-11 22:36:58
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:00:03
 * Github:https://github.com/Andwxa
 * @Description: 发表评论处理
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入数据库
require('../deploy/after_end.php');
//设定字符集
header('Content-Type:text/html;charset=utf-8');
// 网页的名称
$webpage = 'process_withComments';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得当前时间
$time = time();
// 获得内容
$uId = $mysqls_interface->selectIdentityAcquireUIdMysql($_COOKIE['funLoginCookie']);
if(!$uId){
    $_SESSION['showInformation'] = ['./article.php','3','error','非法进入'];
    die(header("Refresh:0;url=../showInformation.php"));
}
$aId = isset($_GET['aId']) ? $_GET['aId'] : '';
$comment = isset($_POST['comment']) ? $_POST['comment'] : '';
// 添加评论
$rs = $mysqls_interface->addCommentMysql($aId,$uId,$comment);
if ($rs) {
    // 更新文章评论次数
    $rst = $mysqls_interface->updateArticleReplyMysql($aId);
    if ($rst){
        $_SESSION['showInformation'] = ["./article.php?id=$aId",'1','success','评论成功！更新评论条数成功！'];
        // 系统日志
        $mysqls_interface->addJournalMysql("发表评论id为 $aId",$uId);
        // 系统消息
        $mcId = $mysqls_interface->addMessageContentMysql("您发表评论id为 $aId");
        $mysqls_interface->addMessageMysql('only',$uId,$mcId);
        // 获得该文章的作者的id
        $auId = $mysqls_interface->selectArticleAcquireUIdByIdMysql($aId);
        // 系统消息
        $mcId = $mysqls_interface->addMessageContentMysql("您id为$aId 的文章获得一条评论");
        $mysqls_interface->addMessageMysql('only',$auId,$mcId);
        // 检查任务
        $mysqls_interface->checkUseerTask($webpage);
    }
    else
        $_SESSION['showInformation'] = ["./article.php?id=$aId",'3','error','评论成功！更新评论条数失败！'];
}else
    $_SESSION['showInformation'] = ["./article.php?id=$aId",'3','error','评论失败，请联系管理人员！'];
die(header("Refresh:0;url=../showInformation.php"));
?>