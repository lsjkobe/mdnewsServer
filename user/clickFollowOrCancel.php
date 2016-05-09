<?php
//关注或取消

require_once "../include.php";

$link = db_connect();

$uid = $_GET['uid'];
$key = $_GET['key'];

if($key == '0'){
	$sql = "INSERT INTO relations(uid,followid,rCreateDate) VALUES( {$uid}, {$_SESSION[USER_ID]}, NOW() ) ";
	//关注+1
	$updateSql = "UPDATE user SET uFollowCount = uFollowCount+1 WHERE uid = {$_SESSION[USER_ID]}";
	//粉丝+1
	$updateSql2 = "UPDATE user SET uFansCount = uFansCount+1 WHERE uid = {$uid}";
}else{
	$sql = "DELETE FROM relations WHERE followid={$_SESSION[USER_ID]} AND uid = {$uid} ";
	//关注+1
	$updateSql = "UPDATE user SET uFollowCount = uFollowCount-1 WHERE uid = {$_SESSION[USER_ID]}";
	//粉丝-1
	$updateSql2 = "UPDATE user SET uFansCount = uFansCount-1 WHERE uid = {$uid}";
}
//是否被关注
$sql2 = "SELECT * FROM relations WHERE uid = {$_SESSION[USER_ID]} AND followid = {$uid}";


if(oneDataInsert($sql)&&updateOneData($updateSql)&&updateOneData($updateSql2)){
	if($key == '0'){
		if(getOneFromDB($sql2)){
			$code = '2';
		}else{
			$code = '1';
		}
	}else{
		$code = '0';
	}

	$resultArray = array(
		'resultCode' => $code
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}else{
	$resultArray = array(
		'resultCode' => '-2'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}

