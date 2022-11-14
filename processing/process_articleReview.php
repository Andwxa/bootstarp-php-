<?php
/*
 * @Date: 2022-10-20 16:19:32
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-03 14:35:19
 * Github:https://github.com/Andwxa
 * @Description: 审核文章处理
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
$webpage = 'process_articleReview';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true,true);
$rAId = $_GET['rAId'];
if ($rAId) {
    // 获得审核表数据
    $rs = $mysqls_interface->selectReviewArticleByrAIdMysql($rAId);
    $rs = $rs->fetch_array();
    $rsUId = $rs['uId'];
    $rsTitle = $rs['title'];
    if ($_POST['dele']) {
        // 删除文章
        $rs = $mysqls_interface->deleReviewArticleMysql($rAId);
        if ($rs) {
            $_SESSION['showInformation'] = ['./articleReviewManagement.php','1','success','删除审核文章成功'];
            // 系统日志
            $mysqls_interface->addJournalMysql("删除审核表中名为《$rsTitle 》的文章",$uId);
            // 系统消息
            $mcId = $mysqls_interface->addMessageContentMysql("您发表了一篇标题为《$rsTitle 》的文章被系统管理员驳回");
            $mysqls_interface->addMessageMysql('only',$uId,$mcId);
        }
        else
            $_SESSION['showInformation'] = ['./articleReviewManagement.php','3','error','删除审核文章失败，请联系管理人员！'];
    }elseif($_POST['update']){
        // 获得审核文章数据
        $rs = $mysqls_interface->selectReviewArticleByrAIdMysql($rAId);
        if ($rs) {
            $rs = $rs->fetch_array();
            $RcId = $rs['cId'];
            $RuId = $rs['uId'];
            $Rtitle = $rs['title'];
            $Rcontent = $rs['content'];
            // 转移到文章
            $rs = $mysqls_interface->addArticleMysql($RcId,$RuId,$Rtitle,$Rcontent);
            if ($rs) {
                // 删除审核文章
                $rs = $mysqls_interface->deleReviewArticleMysql($rAId);
                if ($rs) {
                    $_SESSION['showInformation'] = ['./articleReviewManagement.php','1','success','文章通过成功'];
                        // 系统日志
                        $mysqls_interface->addJournalMysql("删除审核表中名为《$rsTitle 》的文章",$uId);
                        // 系统消息
                        $mcId = $mysqls_interface->addMessageContentMysql("您发表了一篇标题为《$rsTitle 》的文章通过了审核");
                        $mysqls_interface->addMessageMysql('only',$uId,$mcId);
                }else
                    $_SESSION['showInformation'] = ['./articleReviewManagement.php','3','error','删除审核文章失败，请联系管理人员！'];
            }else 
                $_SESSION['showInformation'] = ['./articleReviewManagement.php','3','error','文章添加数据失败，请联系管理人员！'];
        }else
            $_SESSION['showInformation'] = ['./articleReviewManagement.php','3','error','获得文章数据失败，请联系管理人员！'];
    }else
        $_SESSION['showInformation'] = ['./articleReviewManagement.php','3','error','post数据传入失败，请联系管理人员！'];
}else
    $_SESSION['showInformation'] = ['./articleReviewManagement.php','3','error','get数据传入失败，请联系管理人员！'];
die(header("Refresh:0;url=../showInformation.php"));
?>