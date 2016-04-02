<?php
require_once '../include.php';
if(!isLogin()){
	//未登录或session过期
	echo '400';
	exit();
}
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	//不是post请求
	echo '401';
	exit();
}
$mContent = $_POST['content'];
$sid = $_POST['sid'];
$link = db_connect();

$sql = "insert into message(mContent,sid,mType,mCreateDate,uid) values('{$mContent}',{$sid},1,NOW(),{$_SESSION[USER_ID]})";
if(oneDataInsert($sql)){
	$arr = array('resultCode'=>1);  
	echo json_encode($arr);
}else{
	$arr = array('resultCode'=>0);  
	echo json_encode($arr);
}