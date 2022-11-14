<?php
/*
 * @Date: 2022-10-30 22:36:59
 * @LastEditors: Andwxa
 * @LastEditTime: 2022-11-01 15:06:07
 * Github:https://github.com/Andwxa
 * @Description: 正则检查
 */
//验证昵称/单位（2~16位，只允许汉字，英文字母，数字，下划线）
//注意：只支持验证UTF-8编码
function checkUsername($username){
	if(!preg_match('/^[\w\x{4e00}-\x{9fa5}]{2,16}$/u',$username)){
		return '昵称格式不符合要求';
	}
	return true;
}
//验证账号 (长度6-16位，只允许数字)
function checkAccount($account){
	if(!preg_match('/^\w{6,16}$/',$account)){
		return '账号格式不符合要求';
	}
	return true;
}
//验证密码（长度6~16位，只允许英文字母，数字，下划线）
function checkPassword($password){
	if(!preg_match('/^\w{6,16}$/',$password)){
		return '密码格式不符合要求';
	}
	return true;
}

//验证邮箱（不超过40位）
function checkEmail($email){
	if(strlen($email) > 40){
		return '邮箱长度不符合要求';
	}elseif(!preg_match('/^[a-z0-9]+@([a-z0-9]+\.)+[a-z]{2,4}$/i',$email)){
		return '邮箱格式不符合要求';
	}
	return true;
}

//验证QQ号（5~20位）
function checkQQ($qq){
	if ($qq == '未填写') {
		return true;
	}
	if(!preg_match('/^[1-9][0-9]{4,20}$/',$qq)){
		return 'QQ号码格式不符合要求';
	}
	return true;
}
//验证微信号（5~19位）
function checkWechat($wechat){
	if ($wechat == '未填写') {
		return true;
	}
	if(!preg_match('/^[_a-zA-Z0-9]{5,19}+$/isu',$wechat)){
		return '微信号码格式不符合要求';
	}
	return true;
}
//验证手机号码（11位）
function checkPhone($num){
	if ($num == '未填写') {
		return true;
	}
	if(!preg_match('/^1[345789]\d{9}$/',$num)){
		return '手机号码不符合要求';
	}
	return true;
}

//验证URL地址
function checkURL($url){
	if(strlen($url) > 200){
		return 'URL长度不符合要求';
	}elseif(!preg_match('/^http:\/\/[a-z\d-]+(\.[\w\/]+)+$/i',$url)){
		return 'URL格式不符合要求';
	}
	return true;
}
//验证身份证
function checkCard($card){
	
	if ($card == '未填写') {
		return true;
	}
	if(!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/',$card)){
		return '身份证不符合要求';
	}
	return true;
}
?>