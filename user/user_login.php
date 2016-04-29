<?php
//登录
require_once '../include.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
	//不是post请求
	echo '401';
	exit();
}

$userPhone = $_POST['phone'];
$pwd = $_POST['password'];
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

	$_SESSION[USER_SESSION] = $sessionid;
	$_SESSION[USER_PHONE] = $userPhone;
	$_SESSION[USER_ID] = $result['uid'];
	setcookie(USER_SESSION,$sessionid,time()+3600);
	setcookie(USER_PHONE,$userPhone,time()+3600);
	setcookie(USER_ID,$result['uid'],time()+3600);

	$loginArray = array(
		'resultCode' => 1,
		'uid' => $result['uid'],
		'uName' => $result['uName'],
		'uImg' => $result['uHeadImg'],
		'uFansCount' => $result['uFansCount'],
		'uFollowCount' => $result['uFollowCount'],
		'uReleasCount' => $result['uReleasCount'],
		'uStateContent' => $result['uStateContent'],
		'uSexy' => $result['uSexy']
	);
	echo json_encode($loginArray,JSON_UNESCAPED_UNICODE);

}else{
	//用户不存在
	$loginArray = array(
		'resultCode' => 0
	);
	echo json_encode($loginArray,JSON_UNESCAPED_UNICODE);
}