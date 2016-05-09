<?php

require_once "../include.php";

$mid = $_POST['mid'];

$link = db_connect();

$isCollectSql = " SELECT * FROM collection where uid = {$_SESSION[USER_ID]} and mid = {$mid}";

if(getOneFromDB($isCollectSql)){
	$sql = " DELETE FROM collection WHERE uid = {$_SESSION[USER_ID]} and mid = {$mid}";
}else{
	$sql = "INSERT INTO collection(mid,datetime,uid) VALUES({$mid}, NOW(), {$_SESSION[USER_ID]})";
}

if( oneDataInsert($sql) ){
	$resultArray = array(
		'resultCode' => '1'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}else{
	$resultArray = array(
		'resultCode' => '0'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}