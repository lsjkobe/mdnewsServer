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
$sql = "select * from user where uPhoneNum = '{$userPhone}' and uPassword = '{$pwd}' ";
$result = getOneFromDB($sql);
$resultArray  = array('resultCode' => '');
if($result){
	//帐号已存在
	$resultArray['resultCode'] = '101';
	echo json_encode($resultArray);
}else{
	//成功
	$insertSql = "insert into user(uPhoneNum,uPassword) values('{$userPhone}','{$pwd}')";

	if(oneDataInsert($insertSql)){
		$resultArray['resultCode'] = '0';
		echo json_encode($resultArray);
	}else{
		$resultArray['resultCode'] = '404';
		echo json_encode($resultArray);
	}
	
}