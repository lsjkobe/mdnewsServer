<?php
//登录
require_once '../include.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
	//不是post请求
	echo '401';
	return ;
}

$userPhone = $_POST['phone'];
$pwd = md5($_POST['password']);
$link = db_connect();
$sql = "select * from user where uPhoneNum = '{$userPhone}' and uPassword = '{$pwd}' ";
$result = getOneFromDB($sql);
if($result){
	//登录成功
	$sessionid = session_id();
	setcookie(USER_SESSION,$sessionid,time()+24*3600);
	setcookie(USER_PHONE,$userPhone,time()+24*3600);
	setcookie(USER_ID,$result['uid'],time()+24*3600);
	$_SESSION[USER_SESSION] = $sessionid;
	$_SESSION[USER_PHONE] = $userPhone;
	$_SESSION[USER_ID] = $result['uid'];
	echo '1';

}else{
	//用户不存在
	echo '0';
}