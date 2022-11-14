<?php
/*
 * @Date: 2022-10-13 15:07:40
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:56:20
 * Github:https://github.com/Andwxa
 * @Description: 举报处理
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
//引入数据库
require('../deploy/after_end.php');
// 设定字符集
header('Content-Type:text/html;charset=utf-8');
// 网页的名称
$webpage = 'process_inform';
// 连接数据库
$mysqls_interface = new mysqls();
$mysqls_interface->linkMysql();
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 获得类型和id和内容
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$comment = isset($_POST['comment']) ? $_POST['comment'] : '';
// 获得返回的网址
$returnsUrl = $_SESSION['backtrack'];
// 判断类型
if ($type == 'userinfo') {
    $typeName = '用户信息';
}elseif($type == 'article'){
    $typeName = '文章信息';
}elseif ($type == 'comment') {
    $typeName = '评论信息';
}else{
    $_SESSION['showInformation'] = ["$returnsUrl",'3','error','没有收到传递的信息，请联系管理人员！'];
    die(header("Refresh:0;url=../showInformation.php"));
}
if ($type and $id and $comment and $returnsUrl) {
    $rs = $mysqls_interface->addInformMysql($uId,$type,$id,$comment);
    if ($rs) {
        $_SESSION['showInformation'] = ["$returnsUrl",'1','success','举报成功，请等待审核！'];
        // 系统日志
        $mysqls_interface->addJournalMysql("举报 $typeName 类型编号为 $id",$uId);
        // 系统消息
        $mcId = $mysqls_interface->addMessageContentMysql("您对 $typeName 类型编号为 $id 进行举报,管理员将会审核");
        $mysqls_interface->addMessageMysql('only',$uId,$mcId);
    }else
        $_SESSION['showInformation'] = ["$returnsUrl",'3','error','举报失败，没有写入数据库，请联系管理人员！'];
}else
    $_SESSION['showInformation'] = ["$returnsUrl",'3','error','没有收到传递的信息，请联系管理人员！'];
die(header("Refresh:0;url=../showInformation.php"));
?>