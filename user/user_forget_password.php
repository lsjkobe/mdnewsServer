<?php
//注册
require_once '../include.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
	//不是post请求
	echo '401';
	return ;
}

$userPhone = $_POST['phone'];
$pwd = md5($_POST['password']);
$link = db_connect();
$sql = "select * from user where uPhoneNum = '{$userPhone}' ";
$result = getOneFromDB($sql);
$resultArray  = array('resultCode' => '');

if(!$result){
	//帐号没注册
	$resultArray['resultCode'] = '101';
	echo json_encode($resultArray);
}else{
	//成功
	$updateSql = "UPDATE user SET uPassword = '{$pwd}' WHERE uPhoneNum = '{$userPhone}' ";

	if(updateOneData($updateSql)){
		$resultArray['resultCode'] = '1';
		echo json_encode($resultArray);
	}else{
		$resultArray['resultCode'] = '0';
		echo json_encode($resultArray);
	}
	
}