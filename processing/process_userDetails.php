<?php
/*
 * @Date: 2022-10-11 19:17:43
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:59:46
 * Github:https://github.com/Andwxa
 * @Description: 用户详细信息修改
 */
// 取消警告
error_reporting(0);
// 开启session
session_start();
// 引入表单验证函数库
require('../utility/check_form.php');
// 引入数据库
require('../deploy/after_end.php');
// 设定字符集
header('Content-Type:text/html;charset=utf-8');
// 网页的名称
$webpage = 'process_userDetails';
// 当没有表单提交时退出程序
if(empty($_POST)){
	$_SESSION['showInformation'] = ['./userCenter.php','3','error','没有表单提交!'];
	die(header("Refresh:0;url=../showInformation.php"));
}
// 判断表单中各字段是否都已填写
$chcek_fields = array('inputNickName','inputPhone','inputQq','inputWechat','inputCard','inputUnit');
foreach($chcek_fields as $v){
	if(empty($_POST[$v])){
		$_SESSION['showInformation'] = ['./userCenter.php','3','error',"错误：'.$v.'字段不能为空!三秒后自动返回"];
		die(header("Refresh:0;url=../showInformation.php"));
	}
}
// 接收需要处理的表单字段
$inputNickName = $_POST['inputNickName'];
$inputPhone = $_POST['inputPhone'];
$inputQq = $_POST['inputQq'];
$inputWechat = $_POST['inputWechat'];
$inputCard = $_POST['inputCard'];
$inputUnit = $_POST['inputUnit'];
// 字段给存在
if ($inputNickName || $inputPhone|| $inputQq|| $inputWechat|| $inputCard|| $inputUnit) {
    $data = array(
        'account' => $inputNickName,
        'num' => $inputPhone,
        'qq' => $inputQq,
        'wechat' => $inputWechat,
        'card' => $inputCard,
        'unit' => $inputUnit,
    );
    $validate = array(
        'account' => 'checkUsername',
        'num' => 'checkPhone',
        'qq' => 'checkQQ',
        'wechat' => 'checkWechat',
        'card' => 'checkCard',
        'unit' => 'checkUsername',
        
    );
    // 正则判断
    $error = array();
    foreach($validate as $k=>$v){
        //运用可变函数，实现不同字段调用不同函数
        $result = $v($data[$k]);
        if($result !== true){
            $error[] = $result;
        }
    }
    // 正则通过，连接数据库
    if(empty($error)){
        // 连接数据库
        $mysqls_interface = new mysqls();
        $mysqls_interface->linkMysql();
        // 核对cookie的时间
        $uId = $mysqls_interface->inspectionTime(true);
        // 判断昵称是否重复
        $rst = $mysqls_interface->duplicateJudgmentValue('fun_user_information','nickname',$inputNickName);
        if (!$rst) {
            $rst = $mysqls_interface->updateUserInformationMysql($uId,$inputNickName,$inputPhone,$inputQq,$inputWechat,$inputCard,$inputUnit);
            if ($rst) {
                $_SESSION['showInformation'] = ['./userCenter.php','1','success','修改成功！'];
                // 系统日志
                $mysqls_interface->addJournalMysql('对自己用户详细信息进行了修改',$uId);
                // 系统消息
                $mcId = $mysqls_interface->addMessageContentMysql("您对自己用户详细信息进行了修改");
                $mysqls_interface->addMessageMysql('only',$uId,$mcId);
            }else
                $_SESSION['showInformation'] = ['./userCenter.php','3','error','修改失败，请联系管理人员！'];
        }else
            $_SESSION['showInformation'] = ['./userCenter.php','3','error','昵称重复了，请重新命名！'];
    }else{
		foreach($error as $val){
			$check = $check.$val.' ';
		}
		$_SESSION['showInformation'] = ['./userCenter.php','3','error',$check];
	}
    die(header("Refresh:0;url=../showInformation.php"));
}
?>