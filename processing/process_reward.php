<?php
/*
 * @Date: 2022-10-06 23:27:40
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:59:17
 * Github:https://github.com/Andwxa
 * @Description: 打赏判断
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
$webpage = 'process_reward';
// 获得返回的网址
$returnsUrl = $_SESSION['backtrack'];
// 获得要打赏用户id
$textUid = $_GET['textUid'];
// 获得要打赏的积分
$reward = $_POST['integral'];
if (!$reward or !is_numeric($reward) or !$textUid and strlen($reward) < 6) {
    $_SESSION['showInformation'] = ["$returnsUrl",'3','error',"打赏的积分必须是数字且不能为空"];
    die(header("Refresh:0;url=../showInformation.php"));
}
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得积分
$rs = $mysqls_interface->selectUSerInformationById($uId);
$rs = $rs ->fetch_array();
$integral = $rs['integral'];
$usingIntegral = $rs['usingIntegral'];
// 算出可使用的积分
$usedIntegral = $integral - $usingIntegral;
// 如果可用的积分不足
if ($reward > $usedIntegral) {
    $_SESSION['showInformation'] = ["$returnsUrl",'3','error',"可使用的积分不足,您的积分为$usedIntegral,需要花费$reward"];
    die(header("Refresh:0;url=../showInformation.php"));
}
// 当前uId修改不可用积分
$rs = $mysqls_interface->updateUserInformationOfIntegralMysql($uId,$reward,'');
if ($rs) {
    // 修改目标uId的可用积分
    $rs = $mysqls_interface->updateUserInformationOfIntegralMysql($textUid,ceil($reward/2),'ture');
    if ($rs) {
        $_SESSION['showInformation'] = ["$returnsUrl",'1','success','打赏成功'];
        $textNickname = $mysqls_interface->selectUSerInformationAcquireNicknameById($textUid);
        $nickname = $mysqls_interface->selectUSerInformationAcquireNicknameById($uId);
        // 系统日志
        $mysqls_interface->addJournalMysql("用户对 $textNickname 发表的文章打赏 $reward 点积分",$uId);
        // 系统消息
        $mcId = $mysqls_interface->addMessageContentMysql("您对 $textNickname 发表的文章打赏 $reward 点积分");
        $mysqls_interface->addMessageMysql('only',$uId,$mcId);
        // 系统消息
        $mcId = $mysqls_interface->addMessageContentMysql("您被 $nickname 打赏 $reward 点积分");
        $mysqls_interface->addMessageMysql('only',$textUid,$mcId);
    }else
        $_SESSION['showInformation'] = ["$returnsUrl",'3','error','扣除积分成功，打赏失败，请联系管理人员！'];
}else
    $_SESSION['showInformation'] = ["$returnsUrl",'3','error','扣除积分失败，打赏失败，请联系管理人员！'];
die(header("Refresh:0;url=../showInformation.php"));
?>