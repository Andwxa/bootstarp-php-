<?php
/*
 * @Date: 2022-10-02 19:21:04
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-03 14:36:50
 * Github:https://github.com/Andwxa
 * @Description: 删除文章处理
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
$webpage = 'process_deleteArticle';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
$group = $mysqls_interface->selectUSerAcquireGroupById($uId);
if ($_POST['dele']){
    $id = $_GET['id'];
    // 获得这篇文章
    $rs = $mysqls_interface->selectArticleByIdMysql($id);
    if ($rs) {
        $rs = $rs->fetch_array();
        $rsUId = $rs['uId'];
        // 管理员或自己的文章才能删除
        if ($rsUId == $uId or $group == 'admin') {
            // 删除文章
            $rs = $mysqls_interface->deleArticleMysql($id);
            
            // !差删除文章图片!
            
            if ($rs){
                // 更新文章回复条数
                $rs = $mysqls_interface->deleCommentByAIdMysql($id);
                if ($rs){
                    $_SESSION['showInformation'] = ['./articleManagement.php','1','success','文章删除成功'];
                    // 系统日志
                    $mysqls_interface->addJournalMysql("删除自己的文章",$uId);
                    // 系统消息
                    $mcId = $mysqls_interface->addMessageContentMysql("您删除 $id 的文章");
                    $mysqls_interface->addMessageMysql('only',$uId,$mcId);
                }
                else
                    $_SESSION['showInformation'] = ['./articleManagement.php','3','error','文章删除成功，文章评论删除失败，请联系管理人员！'];
            }else
                $_SESSION['showInformation'] = ['./articleManagement.php','3','error','文章删除失败，请联系管理人员！'];
        }else
            $_SESSION['showInformation'] = ['./articleManagement.php','3','error','你没有删除这篇文章的权限！'];
    }else
        $_SESSION['showInformation'] = ['./articleManagement.php','3','error','没有这篇文章，请联系管理人员！'];
    die(header("Refresh:0;url=../showInformation.php"));
}
?>