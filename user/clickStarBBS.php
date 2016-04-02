<?php

require_once '../include.php';
if(!isLogin()){
	//未登录或session过期
	$resultArray = array(
		'resultCode' => '400'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
	exit();
}
$mid = $_GET['mid'];

$link = db_connect();
$selectStarSql = " SELECT * FROM star where uid = {$_SESSION[USER_ID]} and mid = {$mid}";

if(getOneFromDB($selectStarSql)){
	$updateStarSql = " UPDATE message SET mStar = mStar-1 WHERE mid = {$mid}";
	$insertStarSql = " DELETE FROM star WHERE uid = {$_SESSION[USER_ID]} and mid = {$mid}";
	$resultCode = '-1';
}else{
	$updateStarSql = " UPDATE message SET mStar = mStar+1 WHERE mid = {$mid} ";
	$insertStarSql = " INSERT INTO star(uid,mid) VALUES({$_SESSION[USER_ID]},{$mid})";
	$resultCode = '1';
}
if(oneDataInsert($insertStarSql) && updateOneData($updateStarSql)){
	$resultArray = array(
		'resultCode' => $resultCode
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}else{
	$resultArray = array(
		'resultCode' => '0'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}
