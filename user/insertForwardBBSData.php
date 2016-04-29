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
$mid = $_POST['mid'];
$link = db_connect();
//-1表示转发的是没有转发过的圈子
if($sid == -1){
	$sql = "insert into message(mContent,sid,mType,mCreateDate,uid,mLastId) values('{$mContent}',{$mid},1,NOW(),{$_SESSION[USER_ID]},{$mid})";
	$updateForwardSql = " UPDATE message SET mForwardCount = mForwardCount+1 WHERE mid = {$mid}";
	updateOneData($updateForwardSql);
}else{
	$sql = "insert into message(mContent,sid,mType,mCreateDate,uid,mLastId) values('{$mContent}',{$sid},1,NOW(),{$_SESSION[USER_ID]},{$mid})";

	$updateForwardSql = " UPDATE message SET mForwardCount = mForwardCount+1 WHERE mid = {$sid}";
	updateOneData($updateForwardSql);
	$nestID = $mid;
	//查找上一个圈子id如果不是源圈子就加1
	while( $nestID != $sid ){
		$updateForwardSql = " UPDATE message SET mForwardCount = mForwardCount+1 WHERE mid = {$nestID}";
		updateOneData($updateForwardSql);
		$selectNestIdSql = "SELECT mLastId FROM message WHERE mid = {$nestID} ";
		$row = getOneFromDB($selectNestIdSql);
		$nestID = $row['mLastId'];
	}
	
}

if(oneDataInsert($sql)){
	$arr = array('resultCode'=>1);  
	echo json_encode($arr);
}else{
	$arr = array('resultCode'=>0);  
	echo json_encode($arr);
}