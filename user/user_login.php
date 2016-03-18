<?php
//登录
require_once '../include.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
	//不是post请求
	echo '401';
	exit();
}

$userPhone = $_POST['phone'];
$pwd = md5($_POST['password']);
if(!$userPhone || !$pwd){
	//用户名密码为空
	echo '101';
	exit();
}
$link = db_connect();
$sql = "select * from user where uPhoneNum = '{$userPhone}' and uPassword = '{$pwd}' ";
$result = getOneFromDB($sql);
if($result){
	//登录成功
	$sessionid = session_id();
	setcookie(USER_SESSION,$sessionid,time()+7);
	setcookie(USER_PHONE,$userPhone,time()+7);
	setcookie(USER_ID,$result['uid'],time()+7);
	$_SESSION[USER_SESSION] = $sessionid;
	$_SESSION[USER_PHONE] = $userPhone;
	$_SESSION[USER_ID] = $result['uid'];
	echo '1';

}else{
	//用户不存在
	echo '0';
}