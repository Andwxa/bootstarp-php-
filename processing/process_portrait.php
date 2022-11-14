<?php
/*
 * @Date: 2022-10-15 21:34:23
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 14:57:56
 * Github:https://github.com/Andwxa
 * @Description: 头像处理
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
$webpage = 'process_portrait';
// 核对cookie的时间
$uId = $mysqls_interface->inspectionTime(true);
// 判断是否上传头像
if(!empty($_FILES['pic'])){	
	//获取用户上传文件信息
	$pic_info = $_FILES['pic'];
	//判断文件上传到临时文件是否出错
	if($pic_info['error'] >0){
		$error_msg = '上传错误:';
		switch($pic_info['error']){
			case 1: $error_msg .= '文件大小超过了php.ini中upload_max_filesize选项限制的值！'; break;
			case 2: $error_msg .= '文件大小超过了表单中max_file_size选项指定的值！'; break;
			case 3: $error_msg .= '文件只有部分被上传！'; break;
			case 4: $error_msg .= '没有文件被上传！'; break;
			case 6: $error_msg .= '找不到临时文件夹！'; break;
			case 7: $error_msg .= '文件写入失败！'; break;
			default: $error_msg .='未知错误！'; break; 
		}
		$_SESSION['showInformation'] = ['./userCenter.php','3','error',$error_msg];
		die(header("Refresh:0;url=../showInformation.php"));
	}
	// 获取上传文件的类型
	$type = substr(strrchr($pic_info['name'],'.'),1);
	// 判断上传文件类型
	if($type != 'jpg' and $type != 'png' and $type != 'jpeg'){
		$_SESSION['showInformation'] = ['./userCenter.php','3','error','图像类型不符合要求，允许的类型为:jpg,png,jpeg'];
		die(header("Refresh:10;url=../showInformation.php"));
    }
	// 获取原图图像大小
	list($width, $height) = getimagesize($pic_info['tmp_name']);
	// 设置缩略图的最大宽度和高度
	$maxwidth = $maxheight= 200;
	// 自动计算缩略图的宽和高
	if($width > $height){
		// 缩略图的宽等于$maxwidth
		$newwidth = $maxwidth;
		// 计算缩略图的高度
		$newheight = round($newwidth*$height/$width);
	}else{
		// 缩略图的高等于$maxwidth
		$newheight = $maxheight;
		// 计算缩略图的高度
		$newwidth = round($newheight*$width/$height);
	}
	// 绘制缩略图的画布
	$thumb = imageCreateTrueColor($newwidth,$newheight);
	// 依据原图创建一个与原图一样的新的图像
	$source = imagecreatefromjpeg($pic_info['tmp_name']);
	//依据原图创建缩略图
	/**
	  * $thumb 目标图像
	  * $source 原图像
	  * 0,0,0,0 分别代表目标点的x坐标和y坐标，源点的x坐标和y坐标
	  * $newwidth 目标图像的宽
	  * $newheight 目标图像的高
	  * $width 原图像的宽
	  * $height 原图像的高
	  */
	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	// 设置缩略图保存路径
    $data = $mysqls_interface->selectIdentityAcquireUIdMysql($_COOKIE['funLoginCookie']);
	$new_file = '../img/headPortrait/'.$data.'.jpg';
	// 保存缩略图到指定目录
    $preserve =imagejpeg($thumb,$new_file,100);
    if ($preserve){
        $data = $mysqls_interface->selectIdentityAcquireUIdMysql($_COOKIE['funLoginCookie']);
        $rst = $mysqls_interface->updateUserAvatarMysql($data,"./img/headPortrait/$data.jpg");
        if ($rst){
			$_SESSION['showInformation'] = ['./userCenter.php','1','success','图片保存成功！'];
			$mysqls_interface->addJournalMysql('更换头像图片',$uId);
        }else{
			$_SESSION['showInformation'] = ['./userCenter.php','3','error','数据库出错，请联系管理人员！'];
        }
    }else{
		$_SESSION['showInformation'] = ['./userCenter.php','3','error','保存失败，请联系管理人员！'];
    };
}else{
	$_SESSION['showInformation'] = ['./userCenter.php','3','error','未上传图片'];
}
die(header("Refresh:0;url=../showInformation.php"));
?>