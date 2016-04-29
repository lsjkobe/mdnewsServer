<?php

require_once "../include.php";

$uid = $_GET['uid'];
$link = db_connect();

$uSql = "SELECT * FROM user WHERE uid = {$uid} ";
$uData = getOneFromDB($uSql);
if($uData){

	//是否关注
	$rSql1 = "SELECT * FROM relations WHERE uid = {$uid} AND followid = {$_SESSION[USER_ID]}";
	//是否被关注
	$rSql2 = "SELECT * FROM relations WHERE uid = {$_SESSION[USER_ID]} AND followid = {$uid}";
	if( $uid == $_SESSION[USER_ID] ){
		$key = '-1';
	}else if(getOneFromDB($rSql1) && getOneFromDB($rSql2)){
		$key = '2';
	}else if(getOneFromDB($rSql1)){
		$key = '1';
	}else{
		$key = '0';
	}
	$userArray = array(
		'resultCode' => 1,
		'uid' => $uData['uid'],
		'uName' => $uData['uName'],
		'uImg' => $uData['uHeadImg'],
		'uFansCount' => $uData['uFansCount'],
		'uFollowCount' => $uData['uFollowCount'],
		'uReleasCount' => $uData['uReleasCount'],
		'uSexy' => $uData['uSexy'],
		'uStateContent' => $uData['uStateContent'],
		'key' => $key
	);
	echo json_encode($userArray,JSON_UNESCAPED_UNICODE);
}else{
	//用户不存在
	$userArray = array(
		'resultCode' => 0
	);
	echo json_encode($userArray,JSON_UNESCAPED_UNICODE);
}
