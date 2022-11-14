<?php
/*
 * @Date: 2022-10-03 16:25:15
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:59:29
 * Github:https://github.com/Andwxa
 * @Description: 提交任务处理
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
$webpage = 'process_submitTask';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得任务id
$taskId = $_GET['id'];
if ($taskId){
    // 获得任务的进度
    try {
        $rs = $mysqls_interface->selectTaskListByTidUidMysql($taskId,$uId);
        $complete = $rs['complete'];
    } catch (\Throwable $th) {
        $complete = 0;
    }
    // 领取任务
    if ($complete == 0) {
        $rs = $mysqls_interface->addTaskListMysql($taskId,$uId);
        if ($rs) {
            $_SESSION['showInformation'] = ['./userCenter.php','1','success','领取任务成功！'];
            $mysqls_interface->addJournalMysql('领取任务'.$taskId,$uId);
        }else
            $_SESSION['showInformation'] = ['./userCenter.php','3','error','领取任务失败，请联系管理人员！'];
    // 领取奖励
    }elseif($complete == 2){
        $rs = $mysqls_interface->updateTaskListByTidUidMysql($taskId,$uId,'',3);
        if ($rs){
            $rs = $mysqls_interface->selectTaskMysql($taskId);
            $rs = $rs ->fetch_array();
            $taskIntegral = $rs['taskIntegral'];
            $taskName = $rs['$taskName'];
            $rs = $mysqls_interface->updateUserInformationOfIntegralMysql($uId,$taskIntegral,'true');
            if ($rs) {
                $_SESSION['showInformation'] = ['./userCenter.php','1','success','完成任务成功,获得'.$taskIntegral.'积分' ];
                // 系统日志
                $mysqls_interface->addJournalMysql('完成任务'.$taskName,$uId);
            }else
                $_SESSION['showInformation'] = ['./userCenter.php','3','error','完成任务成功，获得积分失败，请联系管理人员！'];
        }
        else
            $_SESSION['showInformation'] = ['./userCenter.php','3','error','完成任务失败，请联系管理人员！'];
    }
}
die(header("Refresh:0;url=../showInformation.php"));
?>