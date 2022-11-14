<?php
/*
 * @Date: 2022-010-01 23:16:06
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:55:51
 * Github:https://github.com/Andwxa
 * @Description: 删除评论处理
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
$webpage = 'process_deleteComment';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
$group = $mysqls_interface->selectUSerAcquireGroupById($uId);
$reply = $_GET['reply'];
$pid = $_GET['aId'];
$page = "./article.php?id=$pid";
if ($reply) {
    // 获得评论的数据
    $rs = $mysqls_interface->selectCommentByCIdMysql($reply);
    // 如果评论存在
    if ($rs) {
        $rs = $rs->fetch_array();
        $aId = $rs['aId'];
        // 获得评论的id
        $commentUId = $rs['uId'];
        // 获得评论文章作者的id
        $ArticleUId = $mysqls_interface->selectArticleAcquireUIdByIdMysql($aId);
        // 自己发表 文章作者 管理员
        if ($commentUId = $uId or $uId == $ArticleUId or $group == 'admin') {
            $rs = $mysqls_interface->deleCommentByRIdMysql($reply);
            if ($rs) {
                // 删除
                $rst = $mysqls_interface->updateArticleReplyMysql($pid);
                if ($rst) {
                    $_SESSION['showInformation'] = ["$page",'1','success','评论删除成功,评论数更新成功'];
                    // 系统日志
                    $mysqls_interface->addJournalMysql("删除评论",$uId);
                    // 系统消息
                    $mcId = $mysqls_interface->addMessageContentMysql("您将自己id= $reply 评论删除了");
                    $mysqls_interface->addMessageMysql('only',$uId,$mcId);
                }else
                    $_SESSION['showInformation'] = ["$page",'3','error','评论删除成功,评论数更新失败，请联系管理人员！'];
            }else
                $_SESSION['showInformation'] = ["$page",'3','error','评论删除失败，请联系管理人员！']; 
        }
    }else
        $_SESSION['showInformation'] = ["$page",'3','error','评论并不存在，请联系管理人员！']; 
}else
    $_SESSION['showInformation'] = ["$page",'3','error','参数错误！'];
die(header("Refresh:0;url=../showInformation.php"));
?>